@extends('layouts.dashboard')
@section('title', 'Task details')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Task details</h1>
            <p class="text-muted">Review your assignment and update its progress.</p>
        </div>
        <div class="page-actions no-print"><a class="btn btn-outline-secondary" href="{{ route('tasks.index') }}"><i
                    class="bi bi-arrow-left me-2"></i>Back</a></div>
    </div>
    <section class="card surface-card">
        <div class="card-body">

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="eyebrow mb-2">TASK</div>
                    <h2>{{ $task->title }}</h2>
                    <div class="mb-4"><span
                            class="status-badge status-{{ str_replace(' ', '-', $task->status) }}">{{ ucfirst($task->status) }}</span>
                    </div>
                    <h5>Description</h5>
                    <p style="white-space:pre-wrap">{{ $task->description ?: 'No description provided.' }}</p>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4" style="background:#f7f8fd">
                        <div class="kv-label">Assigned to</div>
                        <div class="kv-value mb-4">{{ $task->employee?->fullname ?? 'Archived employee' }}</div>
                        <div class="kv-label">Due date</div>
                        <div class="kv-value">{{ $task->due_date->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <form action="{{ route('tasks.status', $task) }}" method="POST">@csrf @method('PATCH')<input
                        type="hidden" name="status" value="{{ $task->status === 'done' ? 'pending' : 'done' }}"><button
                        class="btn btn-primary"
                        type="submit">{{ $task->status === 'done' ? 'Reopen task' : 'Mark as done' }}</button></form>
                @if (auth()->user()->isHr())
                    <a class="btn btn-outline-secondary" href="{{ route('tasks.edit', $task) }}">Edit task</a>
                @endif
            </div>
        </div>
    </section>
@endsection
