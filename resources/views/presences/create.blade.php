@extends('layouts.dashboard')
@section('title', 'Record attendance')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Record attendance</h1>
            <p class="text-muted">
                {{ auth()->user()->isHr() ? 'Enter attendance for your team.' : 'A single click records today’s check-in.' }}
            </p>
        </div><a class="btn btn-outline-secondary no-print" href="{{ route('presences.index') }}"><i
                class="bi bi-arrow-left me-2"></i>Back</a>
    </div>
    <section class="card surface-card">
        <div class="card-body">

            @if (auth()->user()->isHr())
                <div class="alert alert-info">A team member may have only one attendance record per day. Approved leave is
                    recorded automatically.</div>
                @include('presences._form')
            @elseif($todayRecord ?? null)
                <div class="text-center py-5">
                    <div class="metric-icon metric-indigo mx-auto"><i class="bi bi-check2-circle"></i></div>
                    <h3>Already recorded for today</h3>
                    <p class="text-muted">You can clock out from the attendance history if your check-in is still active.
                    </p><a class="btn btn-primary" href="{{ route('presences.index') }}">View attendance</a>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="metric-icon mx-auto" style="background:#e5f9ef;color:#188554"><i class="bi bi-clock"></i>
                    </div>
                    <h3>Ready to start?</h3>
                    <p class="text-muted">Your check-in time is recorded using the server clock. No location permission is
                        needed.</p>
                    <form action="{{ route('presences.store') }}" method="POST">@csrf <button
                            class="btn btn-primary btn-lg" type="submit">Clock in now</button></form>
                </div>
            @endif
        </div>
    </section>
@endsection
