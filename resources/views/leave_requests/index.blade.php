@extends('layouts.dashboard')
@section('title', 'Leave requests')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Leave requests</h1>
            <p class="text-muted">
                {{ auth()->user()->isHr() ? 'Review requests and keep leave attendance in sync.' : 'Submit requests and track approval decisions.' }}
            </p>
        </div>
        <div class="page-actions no-print"><a class="btn btn-primary" href="{{ route('leave-requests.create') }}">Request
                leave</a></div>
    </div>
    <section class="card surface-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table portfolio-table align-middle">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $leaveRequest)
                            <tr>
                                <td class="table-title">{{ $leaveRequest->employee?->fullname ?? 'Archived employee' }}</td>
                                <td>{{ $leaveRequest->leave_type }}</td>
                                <td>{{ $leaveRequest->start_date->format('d M') }} –
                                    {{ $leaveRequest->end_date->format('d M Y') }}</td>
                                <td><span
                                        class="status-badge status-{{ $leaveRequest->status }}">{{ ucfirst($leaveRequest->status) }}</span>
                                </td>
                                <td class="text-end">
                                    @if (auth()->user()->isHr())
                                        @if ($leaveRequest->status !== 'confirmed')
                                            <form method="POST"
                                                action="{{ route('leave-requests.confirm', $leaveRequest) }}"
                                                class="d-inline">@csrf @method('PATCH')<button type="submit"
                                                    class="btn btn-outline-success btn-sm">Approve</button></form>
                                        @endif
                                        @if ($leaveRequest->status !== 'rejected')
                                            <form method="POST"
                                                action="{{ route('leave-requests.reject', $leaveRequest) }}"
                                                class="d-inline" onsubmit="return confirm('Reject this request?')">@csrf
                                                @method('PATCH')<button type="submit"
                                                    class="btn btn-outline-warning btn-sm">Reject</button></form>
                                        @endif
                                        @if ($leaveRequest->status !== 'confirmed')
                                            <a class="btn btn-outline-secondary btn-sm"
                                                href="{{ route('leave-requests.edit', $leaveRequest) }}">Edit</a>
                                        @endif
                                        <form method="POST" action="{{ route('leave-requests.destroy', $leaveRequest) }}"
                                            class="d-inline" onsubmit="return confirm('Archive this request?')">@csrf
                                            @method('DELETE')<button type="submit"
                                                class="btn btn-outline-danger btn-sm">Archive</button></form>
                                    @else<span
                                            class="text-muted small">{{ $leaveRequest->status === 'pending' ? 'Awaiting HR review' : 'Decision recorded' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty<tr>
                                <td colspan="5" class="empty-state"><i class="bi bi-calendar2-heart"></i>No leave
                                    requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $leaveRequests->links() }}</div>
        </div>
    </section>
@endsection
