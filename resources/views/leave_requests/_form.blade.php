@php($leave = $leaveRequest ?? null)
<form method="POST" action="{{ $leave ? route('leave-requests.update', $leave) : route('leave-requests.store') }}">
    @csrf @if ($leave)
        @method('PUT')
    @endif
    <div class="row g-4">
        @if (auth()->user()->isHr())
            <div class="col-12"><label class="form-label" for="employee_id">Employee *</label><select class="form-select"
                    name="employee_id" id="employee_id" required>
                    <option value="">Choose employee</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" @selected(old('employee_id', $leave?->employee_id) == $employee->id)>{{ $employee->fullname }}
                        </option>
                    @endforeach
                </select></div>
        @else<p class="text-muted mb-0">This request will be submitted for your own employee profile.</p>
        @endif
        <div class="col-12"><label class="form-label" for="leave_type">Type of leave *</label><input
                class="form-control" name="leave_type" id="leave_type" list="leave-types"
                value="{{ old('leave_type', $leave?->leave_type) }}" maxlength="100"
                placeholder="Choose or enter a leave type" required><datalist id="leave-types">
                <option value="Annual leave">
                <option value="Sick leave">
                <option value="Family leave">
                <option value="Unpaid leave">
                <option value="Cuti Tahunan">
                <option value="Cuti Sakit">
            </datalist></div>
        <div class="col-md-6"><label class="form-label" for="start_date">Start date *</label><input type="date"
                name="start_date" id="start_date" class="form-control"
                value="{{ old('start_date', $leave?->start_date?->format('Y-m-d')) }}" required></div>
        <div class="col-md-6"><label class="form-label" for="end_date">End date *</label><input type="date"
                name="end_date" id="end_date" class="form-control"
                value="{{ old('end_date', $leave?->end_date?->format('Y-m-d')) }}" required></div>
        <div class="col-12">
            <div class="alert alert-info mb-0">Requests start as <strong>pending</strong>. HR approval generates leave
                attendance records, without replacing existing daily attendance.</div>
        </div>
        <div class="col-12"><button class="btn btn-primary"
                type="submit">{{ $leave ? 'Save changes' : 'Submit request' }}</button> <a class="btn btn-light"
                href="{{ route('leave-requests.index') }}">Cancel</a></div>
    </div>
</form>
