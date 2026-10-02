@extends('layouts.dashboard')
@section('title', 'Tasks')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Tasks</h1>
            <p class="text-muted">
                {{ auth()->user()->isHr() ? 'Assign and monitor team work.' : 'View and update your assigned work.' }}</p>
        </div>
        <div class="page-actions no-print">
            @if (auth()->user()->isHr())
                <a class="btn btn-primary" href="{{ route('tasks.create') }}">Assign task</a>
            @endif
        </div>
    </div>
    <section class="card surface-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table portfolio-table align-middle">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Assigned to</th>
                            <th>Due date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td><a class="table-title" href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
                                </td>
                                <td>{{ $task->employee?->fullname ?? 'Archived employee' }}</td>
                                <td>{{ $task->due_date->format('d M Y') }}</td>
                                <td><span
                                        class="status-badge status-{{ str_replace(' ', '-', $task->status) }}">{{ ucfirst($task->status) }}</span>
                                </td>
                                <td class="text-end"><a class="btn btn-outline-secondary btn-sm"
                                        href="{{ route('tasks.show', $task) }}">Details</a>
                                    <form method="POST" action="{{ route('tasks.status', $task) }}" class="d-inline">@csrf
                                        @method('PATCH')<input type="hidden" name="status"
                                            value="{{ $task->status === 'done' ? 'pending' : 'done' }}"><button
                                            type="submit"
                                            class="btn btn-sm {{ $task->status === 'done' ? 'btn-outline-warning' : 'btn-outline-success' }}">{{ $task->status === 'done' ? 'Reopen' : 'Complete' }}</button>
                                    </form>
                                    @if (auth()->user()->isHr())
                                        <a class="btn btn-outline-primary btn-sm"
                                            href="{{ route('tasks.edit', $task) }}">Edit</a>
                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="d-inline"
                                            onsubmit="return confirm('Archive task?')">@csrf @method('DELETE')<button
                                                type="submit" class="btn btn-outline-danger btn-sm">Archive</button></form>
                                    @endif
                                </td>
                        </tr>@empty<tr>
                                <td colspan="5" class="empty-state"><i class="bi bi-list-check"></i>No tasks yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $tasks->links() }}</div>
        </div>
    </section>
@endsection
