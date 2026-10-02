@extends('layouts.dashboard')
@section('title', 'Employees')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Employees</h1>
            <p class="text-muted">Your team directory and employee access.</p>
        </div>
        <div class="page-actions no-print"><a class="btn btn-primary" href="{{ route('employees.create') }}">Add employee</a>
        </div>
    </div>
    <section class="card surface-card">
        <div class="card-body">

            <form method="GET" action="{{ route('employees.index') }}" class="row g-2 mb-4 no-print">
                <div class="col-12 col-md-5"><label class="visually-hidden" for="employee-search">Search
                        employees</label><input id="employee-search" class="form-control" type="search" name="q"
                        value="{{ request('q') }}" placeholder="Search by name or email"></div>
                <div class="col-8 col-md-3"><label class="visually-hidden" for="employee-status">Filter
                        status</label><select class="form-select" name="status" id="employee-status">
                        <option value="">All statuses</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select></div>
                <div class="col-4 col-md-auto"><button class="btn btn-outline-primary w-100" type="submit">Filter</button>
                </div>
                @if (request()->filled('q') || request()->filled('status'))
                    <div class="col-auto"><a href="{{ route('employees.index') }}" class="btn btn-light">Clear</a></div>
                @endif
            </form>
            <div class="table-responsive">
                <table class="table portfolio-table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Portal</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td><a href="{{ route('employees.show', $employee) }}"
                                        class="table-title">{{ $employee->fullname }}</a>
                                    <div class="text-muted small">{{ $employee->email }}</div>
                                </td>
                                <td>{{ $employee->department?->name ?? 'Archived' }}</td>
                                <td>{{ $employee->role?->title ?? 'Archived' }}</td>
                                <td><span
                                        class="status-badge status-{{ $employee->status }}">{{ ucfirst($employee->status) }}</span>
                                </td>
                                <td>{{ $employee->user ? 'Enabled' : 'Not set' }}</td>
                                <td class="text-end"><a class="btn btn-outline-primary btn-sm"
                                        href="{{ route('employees.show', $employee) }}">View</a>
                                    <a class="btn btn-outline-secondary btn-sm"
                                        href="{{ route('employees.edit', $employee) }}">Edit</a>
                                    <form method="POST" action="{{ route('employees.destroy', $employee) }}"
                                        class="d-inline" onsubmit="return confirm('Archive this employee?')">@csrf
                                        @method('DELETE')<button class="btn btn-outline-danger btn-sm"
                                            type="submit">Archive</button></form>
                                </td>
                            </tr>
                        @empty<tr>
                                <td colspan="6" class="empty-state"><i class="bi bi-people"></i>No employees match your
                                    filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $employees->links() }}</div>
        </div>
    </section>
@endsection
