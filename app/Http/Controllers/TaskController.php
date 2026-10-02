<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::with('employee')
            ->when(!$request->user()->isHr(), fn ($q) => $q->where('assigned_to', $request->user()->employee_id))
            ->orderBy('due_date')->paginate(15);
        return view('tasks.index', compact('tasks'));
    }
    public function create()
    {
        return view('tasks.create', ['employees' => Employee::where('status', 'active')->orderBy('fullname')->get()]);
    }
    public function store(Request $request)
    {
        Task::create($request->validate($this->rules()));
        return redirect()->route('tasks.index')->with('success', 'Task assigned.');
    }
    public function show(Request $request, Task $task)
    {
        $this->guard($request, $task);
        $task->load('employee');
        return view('tasks.show', compact('task'));
    }
    public function edit(Task $task)
    {
        return view('tasks.edit', ['task' => $task,
            'employees' => Employee::where('status', 'active')->orderBy('fullname')->get()]);
    }
    public function update(Request $request, Task $task)
    {
        $task->update($request->validate($this->rules()));
        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }
    public function status(Request $request, Task $task)
    {
        $this->guard($request, $task);
        $data = $request->validate(['status' => ['required', Rule::in(['pending', 'on progress', 'done'])]]);
        $task->update($data);
        return redirect()->route('tasks.index')->with('success', 'Task status updated.');
    }
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task archived.');
    }
    private function guard(Request $request, Task $task): void
    {
        abort_unless($request->user()->isHr() || $task->assigned_to === $request->user()->employee_id, 403);
    }
    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'assigned_to' => ['required', Rule::exists('employees', 'id')->where('status', 'active')->whereNull('deleted_at')],
            'due_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['pending', 'on progress', 'done'])],
        ];
    }
}
