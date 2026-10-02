@extends('layouts.dashboard')
@section('title', 'Payslip')
@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">PEOPLE OPERATIONS</div>
            <h1>Payslip</h1>
            <p class="text-muted">Your printable breakdown of salary, bonuses and deductions.</p>
        </div><a class="btn btn-outline-secondary no-print" href="{{ route('payrolls.index') }}"><i
                class="bi bi-arrow-left me-2"></i>Back</a>
    </div>
    <section class="card surface-card">
        <div class="card-body">

            <div id="print-area">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-5">
                    <div>
                        <div class="eyebrow mb-2">PEOPLEOPS / PAYROLL</div>
                        <h2 class="mb-2">Payslip</h2><span class="text-muted">Portfolio demo - fictional
                            organization</span>
                    </div>
                    <div class="text-end">
                        <div class="kv-label">Pay date</div>
                        <div class="kv-value">{{ $payroll->pay_date->format('d F Y') }}</div>
                        <div class="text-muted small">#PAY-{{ str_pad($payroll->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
                <hr>
                <div class="row g-4 mb-4">
                    <div class="col-sm-6">
                        <div class="kv-label">Employee</div>
                        <div class="kv-value">{{ $payroll->employee?->fullname ?? 'Former employee' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="kv-label">Period</div>
                        <div class="kv-value">{{ $payroll->pay_date->format('F Y') }}</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table portfolio-table mb-4">
                        <thead>
                            <tr>
                                <th>Pay component</th>
                                <th class="text-end">Amount (IDR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Base salary</td>
                                <td class="text-end">Rp {{ number_format($payroll->salary, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Bonuses</td>
                                <td class="text-end">+ Rp {{ number_format($payroll->bonuses, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Deductions</td>
                                <td class="text-end">− Rp {{ number_format($payroll->deductions, 2, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="payslip-total d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="kv-label">NET SALARY</div><strong>Rp
                        {{ number_format($payroll->net_salary, 2, ',', '.') }}</strong>
                </div>
                <p class="form-hint mt-4">This payslip is generated from demonstration data; it is not a real employment or
                    payment record.</p>
            </div>
            <div class="mt-4 no-print"><button onclick="window.print()" type="button" class="btn btn-primary"><i
                        class="bi bi-printer me-2"></i>Print payslip</button></div>
        </div>
    </section>
@endsection
