@extends('layouts.dashboard')
@section('title', 'Edit department')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Edit department</h1>
            <p class="text-muted">Update the details of this department.</p>
        </div>
        <div class="page-actions no-print"><a class="btn btn-outline-secondary" href="{{ route('departments.index') }}"><i
                    class="bi bi-arrow-left me-2"></i>Back</a></div>
    </div>
    <section class="card surface-card">
        <div class="card-body">
            @include('departments._form')
        </div>
    </section>
@endsection
