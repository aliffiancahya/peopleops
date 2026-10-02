@extends('layouts.dashboard')
@section('title', 'Create payslip')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Create payslip</h1>
            <p class="text-muted">Calculate net pay and generate a monthly record.</p>
        </div><a class="btn btn-outline-secondary no-print" href="{{ route('payrolls.index') }}"><i
                class="bi bi-arrow-left me-2"></i>Back</a>
    </div>
    <section class="card surface-card">
        <div class="card-body">
            @include('payrolls._form')
        </div>
    </section>
@endsection
