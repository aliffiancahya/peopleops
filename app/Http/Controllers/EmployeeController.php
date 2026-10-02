<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Presence;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with(['department', 'role', 'user'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->query('q');
                $q->where(fn ($inner) => $inner->where('fullname', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%"));
            })
            ->when(in_array($request->query('status'), ['active', 'inactive'], true),
                fn ($q) => $q->where('status', $request->query('status')))
            ->latest()->paginate(12)->withQueryString();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create', [
            'departments' => Department::where('status', 'active')->orderBy('name')->get(),
            'roles' => Role::orderBy('title')->get(),
        ]);
    }

    private function rules(?Employee $employee = null): array
    {
        return [
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($employee?->id),
                Rule::unique('users', 'email')->ignore($employee?->user?->id)],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'birth_date' => ['required', 'date', 'before:today'],
            'hire_date' => ['required', 'date'],
            'department_id' => ['required', Rule::exists('departments', 'id')->whereNull('deleted_at')],
            'role_id' => ['required', Rule::exists('roles', 'id')->whereNull('deleted_at')],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'salary' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'portal_password' => ['nullable', 'confirmed', Password::min(8)],
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        DB::transaction(function () use ($data) {
            $employee = Employee::create(collect($data)->except('portal_password')->all());
            if (!empty($data['portal_password'])) {
                $employee->user()->create([
                    'name' => $employee->fullname, 'email' => $employee->email,
                    'password' => $data['portal_password'],
                ]);
            }
        });
        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'role', 'user']);
        $counts = Presence::where('employee_id', $employee->id)
            ->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        return view('employees.show', [
            'employee' => $employee,
            'present' => $counts->get('present', 0),
            'absent' => $counts->get('absent', 0),
            'leave' => $counts->get('leave', 0),
        ]);
    }

    public function edit(Employee $employee)
    {
        $employee->load('user');
        return view('employees.edit', [
            'employee' => $employee,
            'departments' => Department::orderBy('name')->get(),
            'roles' => Role::orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->load('user');
        $data = $request->validate($this->rules($employee));
        if ($request->user()->employee_id === $employee->id) {
            if ($data['status'] !== 'active') {
                return back()->withErrors(['status' => 'You cannot deactivate your own account.'])->withInput();
            }
            if ($employee->role?->title === 'HR' && Role::find($data['role_id'])?->title !== 'HR') {
                return back()->withErrors(['role_id' => 'Another HR administrator must change your own HR role.'])->withInput();
            }
        }
        DB::transaction(function () use ($employee, $data) {
            $employee->update(collect($data)->except('portal_password')->all());
            if ($employee->user) {
                $updates = ['name' => $employee->fullname, 'email' => $employee->email];
                if (!empty($data['portal_password'])) $updates['password'] = $data['portal_password'];
                $employee->user->update($updates);
            } elseif (!empty($data['portal_password'])) {
                $employee->user()->create([
                    'name' => $employee->fullname, 'email' => $employee->email,
                    'password' => $data['portal_password'],
                ]);
            }
        });
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Request $request, Employee $employee)
    {
        abort_if($request->user()->employee_id === $employee->id, 422, 'You cannot remove your own employee profile.');
        DB::transaction(function () use ($employee) {
            $employee->user?->delete();
            $employee->delete();
        });
        return redirect()->route('employees.index')->with('success', 'Employee archived.');
    }
}
