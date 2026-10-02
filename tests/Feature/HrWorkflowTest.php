<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Presence;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;

beforeEach(function () {
    $department = Department::create(['name' => 'Engineering', 'status' => 'active']);
    $hrRole = Role::create(['title' => 'HR']);
    $developerRole = Role::create(['title' => 'Developer']);
    $fields = [
        'birth_date' => '1999-06-01', 'hire_date' => '2024-03-01',
        'department_id' => $department->id, 'status' => 'active', 'salary' => 10000000,
    ];
    $this->hrEmployee = Employee::create([...$fields, 'fullname' => 'HR Manager',
        'email' => 'hr@example.test', 'role_id' => $hrRole->id]);
    $this->employee = Employee::create([...$fields, 'fullname' => 'Team Member',
        'email' => 'developer@example.test', 'role_id' => $developerRole->id]);
    $this->otherEmployee = Employee::create([...$fields, 'fullname' => 'Another Member',
        'email' => 'other@example.test', 'role_id' => $developerRole->id]);
    $this->hr = User::factory()->create(['name' => 'HR Manager', 'email' => 'hr@example.test',
        'employee_id' => $this->hrEmployee->id]);
    $this->staff = User::factory()->create(['name' => 'Team Member', 'email' => 'developer@example.test',
        'employee_id' => $this->employee->id]);
});

test('authenticated people have their own dashboard and admin pages are protected', function () {
    $this->actingAs($this->staff)->get('/dashboard')->assertOk()->assertSee('Your work, in focus.');
    $this->actingAs($this->staff)->get('/employees')->assertForbidden();
    $this->actingAs($this->staff)->get('/roles')->assertForbidden();
    $this->actingAs($this->hr)->get('/employees')->assertOk()->assertSee('Team Member');
});

test('unlinked and inactive accounts cannot access the workspace', function () {
    $unlinked = User::factory()->create();
    $this->actingAs($unlinked)->get('/dashboard')->assertForbidden();
    $this->employee->update(['status' => 'inactive']);
    $this->actingAs($this->staff)->get('/dashboard')->assertForbidden();
});

test('only HR can write payroll and staff can see only their own payslip', function () {
    $mine = Payroll::create(['employee_id' => $this->employee->id, 'salary' => 10000000,
        'bonuses' => 0, 'deductions' => 0, 'net_salary' => 10000000, 'pay_date' => now()->toDateString()]);
    $other = Payroll::create(['employee_id' => $this->otherEmployee->id, 'salary' => 10000000,
        'bonuses' => 0, 'deductions' => 0, 'net_salary' => 10000000, 'pay_date' => now()->toDateString()]);
    $this->actingAs($this->staff)->get(route('payrolls.show', $mine))->assertOk();
    $this->actingAs($this->staff)->get(route('payrolls.show', $other))->assertForbidden();
    $this->actingAs($this->staff)->post(route('payrolls.store'), [])->assertForbidden();
});

test('net salary is calculated on the server and duplicate monthly payroll is rejected', function () {
    $data = ['employee_id' => $this->employee->id, 'salary' => 10000000,
        'bonuses' => 500000, 'deductions' => 200000, 'net_salary' => 1,
        'pay_date' => now()->toDateString()];
    $this->actingAs($this->hr)->post(route('payrolls.store'), $data)
        ->assertRedirect(route('payrolls.index'))->assertSessionHasNoErrors();
    $this->assertDatabaseHas('payroll', ['employee_id' => $this->employee->id, 'net_salary' => 10300000]);
    $this->actingAs($this->hr)->post(route('payrolls.store'), $data)->assertSessionHasErrors('pay_date');
});

test('staff can change their own task status but not other employees tasks', function () {
    $mine = Task::create(['title' => 'Mine', 'assigned_to' => $this->employee->id,
        'due_date' => today(), 'status' => 'pending']);
    $other = Task::create(['title' => 'Not mine', 'assigned_to' => $this->otherEmployee->id,
        'due_date' => today(), 'status' => 'pending']);
    $this->actingAs($this->staff)->patch(route('tasks.status', $mine), ['status' => 'done'])
        ->assertRedirect(route('tasks.index'));
    expect($mine->fresh()->status)->toBe('done');
    $this->actingAs($this->staff)->patch(route('tasks.status', $other), ['status' => 'done'])->assertForbidden();
    $this->actingAs($this->staff)->get(route('tasks.show', $other))->assertForbidden();
    $this->actingAs($this->staff)->get('/tasks/done/'.$other->id)->assertNotFound();
});

test('attendance is unique per employee per day and employees can clock out once', function () {
    $this->actingAs($this->staff)->post(route('presences.store'), [])->assertRedirect(route('presences.index'));
    $presence = Presence::where('employee_id', $this->employee->id)->firstOrFail();
    $this->actingAs($this->staff)->post(route('presences.store'), [])->assertSessionHasErrors('date');
    $this->actingAs($this->staff)->patch(route('presences.checkout', $presence))->assertRedirect(route('presences.index'));
    expect($presence->fresh()->check_out)->not()->toBeNull();
    $this->actingAs($this->staff)->patch(route('presences.checkout', $presence))->assertSessionHasErrors('checkout');
});

test('staff cannot impersonate another employee in a leave request', function () {
    $this->actingAs($this->staff)->post(route('leave-requests.store'), [
        'employee_id' => $this->otherEmployee->id, 'leave_type' => 'Annual leave',
        'start_date' => today()->addDays(10)->toDateString(),
        'end_date' => today()->addDays(12)->toDateString(), 'status' => 'confirmed',
    ])->assertRedirect(route('leave-requests.index'));
    $this->assertDatabaseHas('leave_requests', ['employee_id' => $this->employee->id, 'status' => 'pending']);
    $this->assertDatabaseMissing('leave_requests', ['employee_id' => $this->otherEmployee->id]);
});

test('HR approval creates leave attendance and rejection removes only auto-generated records', function () {
    $start = today()->addDays(10);
    $leave = LeaveRequest::create(['employee_id' => $this->employee->id,
        'leave_type' => 'Annual leave', 'start_date' => $start,
        'end_date' => $start->copy()->addDays(2), 'status' => 'pending']);
    $this->actingAs($this->staff)->patch(route('leave-requests.confirm', $leave))->assertForbidden();
    $this->actingAs($this->hr)->patch(route('leave-requests.confirm', $leave))
        ->assertRedirect(route('leave-requests.index'));
    expect(Presence::where('leave_request_id', $leave->id)->count())->toBe(3);
    expect($leave->fresh()->status)->toBe('confirmed');
    $this->actingAs($this->hr)->patch(route('leave-requests.confirm', $leave));
    expect(Presence::where('leave_request_id', $leave->id)->count())->toBe(3);
    $this->actingAs($this->hr)->patch(route('leave-requests.reject', $leave))
        ->assertRedirect(route('leave-requests.index'));
    expect($leave->fresh()->status)->toBe('rejected');
    expect(Presence::where('leave_request_id', $leave->id)->count())->toBe(0);
});

test('HR can provision linked employee login without an open public registration route', function () {
    $role = Role::where('title', 'Developer')->firstOrFail();
    $this->actingAs($this->hr)->post(route('employees.store'), [
        'fullname' => 'New Hire', 'email' => 'new.hire@example.test',
        'birth_date' => '1998-01-01', 'hire_date' => today()->toDateString(),
        'department_id' => $this->employee->department_id, 'role_id' => $role->id,
        'status' => 'active', 'salary' => 9000000,
        'portal_password' => 'NewPassword123!', 'portal_password_confirmation' => 'NewPassword123!',
    ])->assertRedirect(route('employees.index'));
    $this->assertDatabaseHas('users', ['email' => 'new.hire@example.test']);
    expect(User::where('email', 'new.hire@example.test')->firstOrFail()->employee?->fullname)->toBe('New Hire');
});

test('HR cannot demote or deactivate their own account', function () {
    $developer = Role::where('title', 'Developer')->firstOrFail();
    $data = [
        'fullname' => $this->hrEmployee->fullname, 'email' => $this->hrEmployee->email,
        'birth_date' => $this->hrEmployee->birth_date->format('Y-m-d'),
        'hire_date' => $this->hrEmployee->hire_date->format('Y-m-d'),
        'department_id' => $this->hrEmployee->department_id,
        'role_id' => $developer->id, 'status' => 'active', 'salary' => 10000000,
    ];
    $this->actingAs($this->hr)->put(route('employees.update', $this->hrEmployee), $data)
        ->assertSessionHasErrors('role_id');
    expect($this->hrEmployee->fresh()->role->title)->toBe('HR');
    $this->actingAs($this->hr)->put(route('employees.update', $this->hrEmployee), [
        ...$data, 'role_id' => $this->hrEmployee->role_id, 'status' => 'inactive',
    ])->assertSessionHasErrors('status');
});
