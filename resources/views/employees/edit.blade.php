@extends('layouts.dashboard')
@section('title', 'Edit employee')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Edit employee</h1>
            <p class="text-muted">Keep employment information and portal access up to date.</p>
        </div>
        <div class="page-actions no-print"><a class="btn btn-outline-secondary" href="{{ route('employees.index') }}"><i
                    class="bi bi-arrow-left me-2"></i>Back</a></div>
    </div>
    <section class="card surface-card">
        <div class="card-body">
            @include('employees._form')
        </div>
    </section>
@endsection
