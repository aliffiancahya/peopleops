<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index() { return view('roles.index', ['roles' => Role::withCount('employees')->orderBy('title')->get()]); }
    public function create() { return view('roles.create'); }
    public function store(Request $request)
    {
        Role::create($request->validate($this->rules()));
        return redirect()->route('roles.index')->with('success', 'Role created.');
    }
    public function edit(Role $role) { return view('roles.edit', compact('role')); }
    public function update(Request $request, Role $role)
    {
        $data = $request->validate($this->rules($role));
        if ($role->title === 'HR' && $data['title'] !== 'HR') {
            return back()->withErrors(['title' => 'The HR permission role cannot be renamed.']);
        }
        $role->update($data);
        return redirect()->route('roles.index')->with('success', 'Role updated.');
    }
    public function destroy(Role $role)
    {
        if ($role->title === 'HR' || Employee::where('role_id', $role->id)->exists()) {
            return back()->withErrors(['role' => 'This role is required or still assigned to employees.']);
        }
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role archived.');
    }
    private function rules(?Role $role = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role?->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
