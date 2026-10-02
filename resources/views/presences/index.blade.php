@extends('layouts.dashboard')
@section('title', 'Attendance')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Attendance</h1>
            <p class="text-muted">
                {{ auth()->user()->isHr() ? 'Monitor team attendance and manual adjustments.' : 'Clock in, clock out and view your history.' }}
            </p>
        </div>
        <div class="page-actions no-print"><a class="btn btn-primary" href="{{ route('presences.create') }}">Record
                attendance</a></div>
    </div>
    <section class="card surface-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table portfolio-table align-middle">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td class="table-title">{{ $attendance->employee?->fullname ?? 'Archived employee' }}</td>
                                <td>{{ $attendance->date->format('d M Y') }}</td>
                                <td>{{ $attendance->check_in?->format('H:i') ?? '—' }}</td>
                                <td>{{ $attendance->check_out?->format('H:i') ?? '—' }}</td>
                                <td><span
                                        class="status-badge status-{{ $attendance->status }}">{{ ucfirst($attendance->status) }}</span>
                                </td>
                                <td class="text-end">
                                    @if (
                                        $attendance->employee_id === auth()->user()->employee_id &&
                                            $attendance->status === 'present' &&
                                            !$attendance->check_out &&
                                            $attendance->date->isToday())
                                        <form method="POST" action="{{ route('presences.checkout', $attendance) }}"
                                            class="d-inline">@csrf @method('PATCH')<button
                                                class="btn btn-outline-success btn-sm" type="submit">Clock out</button>
                                        </form>
                                    @endif
                                    @if (auth()->user()->isHr() && !$attendance->leave_request_id)
                                        <a class="btn btn-outline-secondary btn-sm"
                                            href="{{ route('presences.edit', $attendance) }}">Edit</a>
                                        <form method="POST" action="{{ route('presences.destroy', $attendance) }}"
                                            class="d-inline" onsubmit="return confirm('Archive attendance record?')">@csrf
                                            @method('DELETE')<button type="submit"
                                                class="btn btn-outline-danger btn-sm">Archive</button></form>
                                    @endif
                                    @if ($attendance->leave_request_id)
                                        <small class="text-muted">From approved leave</small>
                                    @endif
                                </td>
                        </tr>@empty<tr>
                                <td colspan="6" class="empty-state"><i class="bi bi-calendar2-check"></i>No attendance
                                    records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $attendances->links() }}</div>
        </div>
    </section>
@endsection
