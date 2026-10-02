<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PresencesController extends Controller
{
    public function index(Request $request)
    {
        $attendances = Presence::with('employee')
            ->when(!$request->user()->isHr(), fn ($q) => $q->where('employee_id', $request->user()->employee_id))
            ->latest('date')->latest('id')->paginate(15);
        return view('presences.index', compact('attendances'));
    }

    public function create(Request $request)
    {
        return view('presences.create', [
            'employees' => $request->user()->isHr()
                ? Employee::where('status', 'active')->orderBy('fullname')->get() : collect(),
            'todayRecord' => Presence::where('employee_id', $request->user()->employee_id)
                ->whereDate('date', today())->first(),
        ]);
    }

    public function store(Request $request)
    {
        if ($request->user()->isHr()) {
            $data = $request->validate([
                'employee_id' => ['required', Rule::exists('employees', 'id')->whereNull('deleted_at')],
                'date' => ['required', 'date'],
                'check_in' => ['nullable', 'date'],
                'check_out' => ['nullable', 'date', 'after_or_equal:check_in'],
                'status' => ['required', Rule::in(['present', 'absent', 'leave'])],
            ]);
            if ($data['status'] === 'present' && empty($data['check_in'])) {
                throw ValidationException::withMessages(['check_in' => 'A present record requires a check-in time.']);
            }
            $data['check_in'] = $data['check_in'] ?? null;
            $data['check_out'] = $data['check_out'] ?? null;
        } else {
            $data = [
                'employee_id' => $request->user()->employee_id,
                'date' => today()->toDateString(),
                'check_in' => now(),
                'check_out' => null,
                'status' => 'present',
            ];
        }
        DB::transaction(function () use ($data) {
            if (Presence::where('employee_id', $data['employee_id'])->whereDate('date', $data['date'])->lockForUpdate()->exists()) {
                throw ValidationException::withMessages(['date' => 'Attendance has already been recorded for this employee and date.']);
            }
            Presence::create($data);
        });
        return redirect()->route('presences.index')->with('success', 'Attendance recorded.');
    }

    public function checkout(Request $request, Presence $presence)
    {
        abort_unless($presence->employee_id === $request->user()->employee_id, 403);
        if ($presence->status !== 'present' || $presence->check_out || !$presence->check_in ||
            !$presence->date->isToday()) {
            return back()->withErrors(['checkout' => 'Only today’s active check-in can be checked out.']);
        }
        $presence->update(['check_out' => now()]);
        return redirect()->route('presences.index')->with('success', 'Checked out successfully.');
    }

    public function edit(Presence $presence)
    {
        abort_if($presence->leave_request_id, 422, 'Manage leave-generated attendance through leave requests.');
        return view('presences.edit', [
            'presence' => $presence,
            'employees' => Employee::orderBy('fullname')->get(),
        ]);
    }

    public function update(Request $request, Presence $presence)
    {
        abort_if($presence->leave_request_id, 422, 'Manage leave-generated attendance through leave requests.');
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'date' => ['required', 'date'],
            'check_in' => ['nullable', 'date'],
            'check_out' => ['nullable', 'date', 'after_or_equal:check_in'],
            'status' => ['required', Rule::in(['present', 'absent', 'leave'])],
        ]);
        if ($data['status'] === 'present' && empty($data['check_in'])) {
            throw ValidationException::withMessages(['check_in' => 'A present record requires a check-in time.']);
        }
        if (Presence::where('employee_id', $data['employee_id'])->whereDate('date', $data['date'])
            ->where('id', '!=', $presence->id)->exists()) {
            throw ValidationException::withMessages(['date' => 'Attendance already exists for this employee and date.']);
        }
        $presence->update($data);
        return redirect()->route('presences.index')->with('success', 'Attendance updated.');
    }
    public function destroy(Presence $presence)
    {
        abort_if($presence->leave_request_id, 422, 'Manage leave-generated attendance through leave requests.');
        $presence->delete();
        return redirect()->route('presences.index')->with('success', 'Attendance archived.');
    }
}
