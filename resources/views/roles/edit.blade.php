@extends('layouts.dashboard')
@section('title', 'Edit role')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Edit role</h1>
            <p class="text-muted">Update a role without disrupting access permissions.</p>
        </div>
        <div class="page-actions no-print"><a class="btn btn-outline-secondary" href="{{ route('roles.index') }}"><i
                    class="bi bi-arrow-left me-2"></i>Back</a></div>
    </div>
    <section class="card surface-card">
        <div class="card-body">
            @include('roles._form')
        </div>
    </section>
@endsection
