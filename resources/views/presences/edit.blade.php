@extends('layouts.dashboard')
@section('title', 'Edit attendance')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Edit attendance</h1>
            <p class="text-muted">Adjust an existing manual attendance entry.</p>
        </div><a class="btn btn-outline-secondary no-print" href="{{ route('presences.index') }}"><i
                class="bi bi-arrow-left me-2"></i>Back</a>
    </div>
    <section class="card surface-card">
        <div class="card-body">
            @include('presences._form')
        </div>
    </section>
@endsection
