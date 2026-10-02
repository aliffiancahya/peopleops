@extends('layouts.dashboard')
@section('title', 'Payroll')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Payroll</h1>
            <p class="text-muted">
                {{ auth()->user()->isHr() ? 'Create payslips and track monthly pay.' : 'Review your own monthly payslips.' }}
            </p>
        </div>
        <div class="page-actions no-print">
            @if (auth()->user()->isHr())
                <a class="btn btn-primary" href="{{ route('payrolls.create') }}">Create payslip</a>
            @endif
        </div>
    </div>
    <section class="card surface-card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table portfolio-table align-middle">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Pay date</th>
                            <th>Base salary</th>
                            <th>Net salary</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                            <tr>
                                <td class="table-title">{{ $payroll->employee?->fullname ?? 'Archived employee' }}</td>
                                <td>{{ $payroll->pay_date->format('d M Y') }}</td>
                                <td>Rp {{ number_format($payroll->salary, 0, ',', '.') }}</td>
                                <td><strong>Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</strong></td>
                                <td class="text-end"><a class="btn btn-outline-primary btn-sm"
                                        href="{{ route('payrolls.show', $payroll) }}">View slip</a>
                                    @if (auth()->user()->isHr())
                                        <a class="btn btn-outline-secondary btn-sm"
                                            href="{{ route('payrolls.edit', $payroll) }}">Edit</a>
                                        <form method="POST" action="{{ route('payrolls.destroy', $payroll) }}"
                                            class="d-inline" onsubmit="return confirm('Archive this payslip?')">@csrf
                                            @method('DELETE')<button type="submit"
                                                class="btn btn-outline-danger btn-sm">Archive</button></form>
                                    @endif
                                </td>
                        </tr>@empty<tr>
                                <td colspan="5" class="empty-state"><i class="bi bi-receipt"></i>No payslips yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $payrolls->links() }}</div>
        </div>
    </section>
@endsection
