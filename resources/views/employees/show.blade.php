@extends('layouts.dashboard')
@section('title', 'Employee profile')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Employee profile</h1>
            <p class="text-muted">Employment information and attendance summary.</p>
        </div>
        <div class="page-actions no-print"><a class="btn btn-outline-secondary" href="{{ route('employees.index') }}"><i
                    class="bi bi-arrow-left me-2"></i>Back</a></div>
    </div>
    <section class="card surface-card">
        <div class="card-body">

            <div class="row g-4">
                <div class="col-md-7">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="eyebrow">PERSONAL INFORMATION</div>
                            <h3>{{ $employee->fullname }}</h3><span
                                class="status-badge status-{{ $employee->status }}">{{ ucfirst($employee->status) }}</span>
                        </div>
                        <div class="col-sm-6">
                            <div class="kv-label">Email</div>
                            <div class="kv-value">{{ $employee->email }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="kv-label">Phone</div>
                            <div class="kv-value">{{ $employee->phone_number ?: '—' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="kv-label">Department</div>
                            <div class="kv-value">{{ $employee->department?->name ?? 'Archived' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="kv-label">Role</div>
                            <div class="kv-value">{{ $employee->role?->title ?? 'Archived' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="kv-label">Hire date</div>
                            <div class="kv-value">{{ $employee->hire_date->format('d F Y') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="kv-label">Portal account</div>
                            <div class="kv-value">{{ $employee->user ? 'Enabled' : 'Not created' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="kv-label">Monthly salary</div>
                            <div class="kv-value">Rp {{ number_format($employee->salary, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="kv-label">Address</div>
                            <div class="kv-value">{{ $employee->address ?: '—' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="p-4 rounded-4" style="background:#f7f8fd">
                        <div class="eyebrow mb-3">ATTENDANCE SNAPSHOT</div>
                        <div class="d-flex justify-content-between border-bottom py-3">
                            <span>Present</span><strong>{{ $present }} days</strong></div>
                        <div class="d-flex justify-content-between border-bottom py-3">
                            <span>Absent</span><strong>{{ $absent }} days</strong></div>
                        <div class="d-flex justify-content-between py-3"><span>Leave</span><strong>{{ $leave }}
                                days</strong></div>
                    </div>
                </div>
            </div>
            <div class="mt-4"><a class="btn btn-primary" href="{{ route('employees.edit', $employee) }}">Edit
                    employee</a></div>
        </div>
    </section>
@endsection
