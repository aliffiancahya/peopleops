@extends('layouts.dashboard')
@section('title', 'Edit task')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Edit task</h1>
            <p class="text-muted">Update the assignment, deadline or status.</p>
        </div>
        <div class="page-actions no-print"><a class="btn btn-outline-secondary" href="{{ route('tasks.index') }}"><i
                    class="bi bi-arrow-left me-2"></i>Back</a></div>
    </div>
    <section class="card surface-card">
        <div class="card-body">
            @include('tasks._form')
        </div>
    </section>
@endsection
