@extends('layouts.dashboard')
@section('title', 'Departments')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Departments</h1>
            <p class="text-muted">Organize the teams powering your company.</p>
        </div>
        <div class="page-actions no-print"><a class="btn btn-primary" href="{{ route('departments.create') }}">Add
                department</a></div>
    </div>
    <section class="card surface-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table portfolio-table align-middle">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Description</th>
                            <th>Employees</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                            <tr>
                                <td class="table-title">{{ $department->name }}</td>
                                <td class="text-muted">{{ $department->description ?: '—' }}</td>
                                <td>{{ $department->employees_count }}</td>
                                <td><span
                                        class="status-badge status-{{ $department->status }}">{{ ucfirst($department->status) }}</span>
                                </td>
                                <td class="text-end"><a class="btn btn-outline-secondary btn-sm"
                                        href="{{ route('departments.edit', $department) }}">Edit</a>
                                    <form action="{{ route('departments.destroy', $department) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Archive this department?')">@csrf
                                        @method('DELETE')<button class="btn btn-outline-danger btn-sm" type="submit"
                                            @disabled($department->employees_count > 0)>Archive</button></form>
                                </td>
                            </tr>
                        @empty<tr>
                                <td colspan="5" class="empty-state"><i class="bi bi-diagram-3"></i>No departments yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
