<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Presence;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $leaveRequests = LeaveRequest::with('employee')
            ->when(!$request->user()->isHr(), fn ($q) => $q->where('employee_id', $request->user()->employee_id))
            ->latest()->paginate(15);
        return view('leave_requests.index', compact('leaveRequests'));
    }
    public function create()
    {
        return view('leave_requests.create', ['employees' => Employee::where('status', 'active')->orderBy('fullname')->get()]);
    }
    public function store(Request $request)
    {
        $data = $request->validate($this->rules($request->user()->isHr()));
        if (!$request->user()->isHr()) $data['employee_id'] = $request->user()->employee_id;
        $this->ensureNoOverlap($data);
        $data['status'] = 'pending';
        LeaveRequest::create($data);
        return redirect()->route('leave-requests.index')->with('success', 'Leave request submitted for review.');
    }
    public function edit(LeaveRequest $leaveRequest)
    {
        abort_if($leaveRequest->status === 'confirmed', 422, 'Reject approved leave before editing its dates.');
        return view('leave_requests.edit', [
            'leaveRequest' => $leaveRequest,
            'employees' => Employee::orderBy('fullname')->get(),
            'statuses' => ['pending', 'confirmed', 'rejected'],
        ]);
    }
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        abort_if($leaveRequest->status === 'confirmed', 422, 'Reject approved leave before editing its dates.');
        $data = $request->validate($this->rules(true));
        $this->ensureNoOverlap($data, $leaveRequest);
        $leaveRequest->update([...$data, 'status' => 'pending']);
        return redirect()->route('leave-requests.index')->with('success', 'Leave request updated and returned to pending.');
    }
    public function confirm(LeaveRequest $leaveRequest)
    {
        DB::transaction(function () use ($leaveRequest) {
            $leaveRequest = LeaveRequest::whereKey($leaveRequest->id)->lockForUpdate()->firstOrFail();
            if ($leaveRequest->status === 'confirmed') return;
            $this->ensureNoOverlap($leaveRequest->only(['employee_id', 'start_date', 'end_date']), $leaveRequest);
            foreach (CarbonPeriod::create($leaveRequest->start_date, $leaveRequest->end_date) as $day) {
                // Preserve an existing manual attendance record instead of overwriting it.
                Presence::firstOrCreate(
                    ['employee_id' => $leaveRequest->employee_id, 'date' => $day->toDateString()],
                    ['leave_request_id' => $leaveRequest->id, 'status' => 'leave']
                );
            }
            $leaveRequest->update(['status' => 'confirmed']);
        });
        return redirect()->route('leave-requests.index')->with('success', 'Leave approved and attendance updated.');
    }
    public function reject(LeaveRequest $leaveRequest)
    {
        DB::transaction(function () use ($leaveRequest) {
            Presence::where('leave_request_id', $leaveRequest->id)->delete();
            $leaveRequest->update(['status' => 'rejected']);
        });
        return redirect()->route('leave-requests.index')->with('success', 'Leave rejected.');
    }
    public function destroy(LeaveRequest $leaveRequest)
    {
        DB::transaction(function () use ($leaveRequest) {
            Presence::where('leave_request_id', $leaveRequest->id)->delete();
            $leaveRequest->delete();
        });
        return redirect()->route('leave-requests.index')->with('success', 'Leave request archived.');
    }
    private function rules(bool $isHr): array
    {
        return [
            'employee_id' => [$isHr ? 'required' : 'nullable', 'exists:employees,id'],
            'leave_type' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }
    private function ensureNoOverlap(array $data, ?LeaveRequest $current = null): void
    {
        $overlap = LeaveRequest::where('employee_id', $data['employee_id'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->when($current, fn ($q) => $q->where('id', '!=', $current->id))
            ->whereDate('start_date', '<=', $data['end_date'])
            ->whereDate('end_date', '>=', $data['start_date'])->exists();
        if ($overlap) throw ValidationException::withMessages(['start_date' => 'An overlapping leave request already exists.']);
    }
}
