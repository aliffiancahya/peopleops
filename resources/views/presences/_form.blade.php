@php($presence = $presence ?? null)
<form method="POST" action="{{ $presence ? route('presences.update', $presence) : route('presences.store') }}">
    @csrf @if ($presence)
        @method('PUT')
    @endif
    <div class="row g-4">
        <div class="col-md-6"><label class="form-label" for="employee_id">Employee *</label><select name="employee_id"
                id="employee_id" class="form-select" required>
                <option value="">Choose employee</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected(old('employee_id', $presence?->employee_id) == $employee->id)>{{ $employee->fullname }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3"><label class="form-label" for="date">Attendance date *</label><input type="date"
                name="date" id="date" class="form-control"
                value="{{ old('date', $presence?->date?->format('Y-m-d') ?? today()->toDateString()) }}" required></div>
        <div class="col-md-3"><label class="form-label" for="status">Status *</label><select class="form-select"
                id="status" name="status" required>
                @foreach (['present' => 'Present', 'absent' => 'Absent', 'leave' => 'Leave'] as $status => $label)
                    <option value="{{ $status }}" @selected(old('status', $presence?->status ?? 'present') === $status)>{{ $label }}</option>
                @endforeach
            </select></div>
        <div class="col-md-6"><label class="form-label" for="check_in">Check-in time</label><input type="datetime-local"
                id="check_in" name="check_in" class="form-control"
                value="{{ old('check_in', $presence?->check_in?->format('Y-m-d\TH:i')) }}">
            <p class="form-hint">Required for present status.</p>
        </div>
        <div class="col-md-6"><label class="form-label" for="check_out">Check-out time</label><input
                type="datetime-local" id="check_out" name="check_out" class="form-control"
                value="{{ old('check_out', $presence?->check_out?->format('Y-m-d\TH:i')) }}"></div>
        <div class="col-12"><button class="btn btn-primary"
                type="submit">{{ $presence ? 'Save attendance' : 'Record attendance' }}</button> <a
                class="btn btn-light" href="{{ route('presences.index') }}">Cancel</a></div>
    </div>
</form>
