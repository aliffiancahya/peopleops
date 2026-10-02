<?php

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Department;

function makePortalUser(): User
{
    $department = Department::create(['name' => 'Engineering', 'status' => 'active']);
    $role = Role::create(['title' => 'Developer']);
    $employee = Employee::create([
        'fullname' => 'Example Employee', 'email' => 'example.employee@demo.test',
        'birth_date' => '1998-01-01', 'hire_date' => '2024-01-01',
        'department_id' => $department->id, 'role_id' => $role->id,
        'status' => 'active', 'salary' => 10000000,
    ]);
    return User::factory()->create(['employee_id' => $employee->id, 'email' => $employee->email]);
}

test('profile page is displayed', function () {
    $user = makePortalUser();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = makePortalUser();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertSame('test@example.com', $user->employee->fresh()->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = makePortalUser();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = makePortalUser();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = makePortalUser();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});
