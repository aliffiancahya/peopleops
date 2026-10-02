@extends('layouts.dashboard')
@section('title', 'Roles')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Roles</h1>
            <p class="text-muted">Define the positions in your organization.</p>
        </div>
        <div class="page-actions no-print"><a class="btn btn-primary" href="{{ route('roles.create') }}">Add role</a></div>
    </div>
    <section class="card surface-card">
        <div class="card-body">

            <div class="alert alert-info">The <strong>HR</strong> role grants administrative access. Other roles receive
                personal self-service access.</div>
            <div class="table-responsive">
                <table class="table portfolio-table align-middle">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Description</th>
                            <th>Employees</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td class="table-title">{{ $role->title }}</td>
                                <td>{{ $role->description ?: '—' }}</td>
                                <td>{{ $role->employees_count }}</td>
                                <td class="text-end"><a class="btn btn-outline-secondary btn-sm"
                                        href="{{ route('roles.edit', $role) }}">Edit</a>
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Archive this role?')">@csrf @method('DELETE')<button
                                            class="btn btn-outline-danger btn-sm" @disabled($role->title === 'HR' || $role->employees_count > 0)
                                            type="submit">Archive</button></form>
                                </td>
                            </tr>
                        @empty<tr>
                                <td colspan="4" class="empty-state">No roles yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
