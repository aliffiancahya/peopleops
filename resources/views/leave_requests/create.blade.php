@extends('layouts.dashboard')
@section('title', 'Request leave')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Request leave</h1>
            <p class="text-muted">Submit time off for HR review.</p>
        </div><a class="btn btn-outline-secondary no-print" href="{{ route('leave-requests.index') }}"><i
                class="bi bi-arrow-left me-2"></i>Back</a>
    </div>
    <section class="card surface-card">
        <div class="card-body">
            @include('leave_requests._form')
        </div>
    </section>
@endsection
