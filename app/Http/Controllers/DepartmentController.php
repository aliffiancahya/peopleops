<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index() { return view('departments.index', ['departments' => Department::withCount('employees')->orderBy('name')->get()]); }
    public function create() { return view('departments.create'); }
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        Department::create($data);
        return redirect()->route('departments.index')->with('success', 'Department created.');
    }
    public function edit(Department $department) { return view('departments.edit', compact('department')); }
    public function update(Request $request, Department $department)
    {
        $department->update($request->validate($this->rules($department)));
        return redirect()->route('departments.index')->with('success', 'Department updated.');
    }
    public function destroy(Department $department)
    {
        if (Employee::where('department_id', $department->id)->exists()) {
            return back()->withErrors(['department' => 'Move employees to another department before archiving this one.']);
        }
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department archived.');
    }
    private function rules(?Department $department = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->ignore($department?->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
