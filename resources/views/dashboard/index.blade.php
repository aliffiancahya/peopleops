@extends('layouts.dashboard')

@section('title', 'Overview')

@section('content')
    <div class="page-heading heading-row mb-4">
        <div>
            <div class="eyebrow">AT A GLANCE</div>
            <h1>{{ $isHr ? 'People, at a glance.' : 'Your work, in focus.' }}</h1>
            <p class="text-muted mb-0">
                {{ $isHr ? 'Live metrics across your team and operations.' : 'A clear view of your tasks, attendance and requests.' }}
            </p>
        </div>

        <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
            @if ($isHr)
                <a class="btn btn-primary" href="{{ route('employees.create') }}">
                    Add employee
                </a>
            @else
                <a class="btn btn-primary" href="{{ route('presences.create') }}">
                    Clock in
                </a>
            @endif
        </div>
    </div>

    {{-- Metric cards --}}
    <div class="row g-4 mb-4">
        @if ($isHr)
            <div class="col-6 col-xl-3">
                <a class="metric-card metric-indigo" href="{{ route('employees.index') }}">
                    <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-people d-flex lh-1"></i>
                    </span>
                    <span class="metric-label">Active employees</span>
                    <strong>{{ number_format($employeeCount) }}</strong>
                    <span class="metric-action">
                        View team <i class="bi bi-arrow-up-right"></i>
                    </span>
                </a>
            </div>

            <div class="col-6 col-xl-3">
                <a class="metric-card metric-cyan" href="{{ route('departments.index') }}">
                    <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-diagram-3 d-flex lh-1"></i>
                    </span>
                    <span class="metric-label">Departments</span>
                    <strong>{{ $departmentCount }}</strong>
                    <span class="metric-action">
                        Manage departments <i class="bi bi-arrow-up-right"></i>
                    </span>
                </a>
            </div>
        @else
            <div class="col-6 col-xl-3">
                <a class="metric-card metric-indigo" href="{{ route('tasks.index') }}">
                    <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-list-check d-flex lh-1"></i>
                    </span>
                    <span class="metric-label">Open tasks</span>
                    <strong>{{ $openTasks }}</strong>
                    <span class="metric-action">
                        View tasks <i class="bi bi-arrow-up-right"></i>
                    </span>
                </a>
            </div>

            <div class="col-6 col-xl-3">
                <a class="metric-card metric-cyan" href="{{ route('presences.index') }}">
                    <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-calendar2-check d-flex lh-1"></i>
                    </span>
                    <span class="metric-label">Days present this month</span>
                    <strong>{{ $presenceCount }}</strong>
                    <span class="metric-action">
                        View attendance <i class="bi bi-arrow-up-right"></i>
                    </span>
                </a>
            </div>
        @endif

        <div class="col-6 col-xl-3">
            <a class="metric-card metric-mint" href="{{ route('leave-requests.index') }}">
                <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-calendar2-week d-flex lh-1"></i>
                </span>
                <span class="metric-label">Pending leave</span>
                <strong>{{ $pendingLeaves }}</strong>
                <span class="metric-action">
                    View requests <i class="bi bi-arrow-up-right"></i>
                </span>
            </a>
        </div>

        <div class="col-6 col-xl-3">
            <a class="metric-card metric-amber" href="{{ $isHr ? route('tasks.index') : route('payrolls.index') }}">
                <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                    <i class="bi {{ $isHr ? 'bi-check2-square' : 'bi-receipt' }} d-flex lh-1"></i>
                </span>
                <span class="metric-label">
                    {{ $isHr ? 'Open tasks' : 'Payslips' }}
                </span>
                <strong>
                    {{ $isHr ? $openTasks : count(array_filter($payrollSeries, fn($value) => $value > 0)) }}
                </strong>
                <span class="metric-action">
                    {{ $isHr ? 'Review work' : 'Review pay' }}
                    <i class="bi bi-arrow-up-right"></i>
                </span>
            </a>
        </div>
    </div>

    {{-- Attendance and payroll charts --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-7">
            <div class="card chart-card h-100">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">Attendance trend</h3>
                        <p>Days marked present - last six months</p>
                    </div>
                    <span class="soft-label">LIVE DATA</span>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="presence-chart" aria-label="Attendance over six months" role="img">
                        </canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payroll overview --}}
        <div class="col-12 col-lg-5">
            <div class="card chart-card h-100">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        {{ $isHr ? 'Payroll overview' : 'My salary history' }}
                    </h3>
                    <p>Net payroll by month - IDR</p>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="payroll-chart" aria-label="Monthly payroll totals in Indonesian rupiah" role="img">
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest tasks --}}
    <div class="card panel-card mb-4">
        <div class="card-header border-0 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title">
                    {{ $isHr ? 'Recently created tasks' : 'Your latest tasks' }}
                </h3>
                <p>Keep track of work that needs attention</p>
            </div>

            <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary btn-sm">
                View all
            </a>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-hover portfolio-table align-middle">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Owner</th>
                            <th>Due</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($latestTasks as $task)
                            <tr>
                                <td>
                                    <a class="table-title" href="{{ route('tasks.show', $task) }}">
                                        {{ $task->title }}
                                    </a>
                                </td>

                                <td>
                                    {{ $task->employee?->fullname ?? 'Former employee' }}
                                </td>

                                <td>
                                    {{ $task->due_date->format('d M Y') }}
                                </td>

                                <td>
                                    <span class="status-badge status-{{ str_replace(' ', '-', $task->status) }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    No tasks yet. Your next update will appear here.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('mazer/assets/extensions/chart.js/chart.umd.js') }}"></script>

    <script>
        (() => {
            const labels = @json($months);
            const attendance = @json($presenceSeries);
            const payroll = @json($payrollSeries);

            const colors = {
                primary: '#6259e8',
                cyan: '#28abbb',
                text: '#8993aa',
                grid: 'rgba(148, 163, 184, .13)'
            };

            const baseOptions = {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: colors.text
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: colors.grid
                        },
                        ticks: {
                            color: colors.text
                        }
                    }
                }
            };

            // Attendance chart
            new Chart(document.getElementById('presence-chart'), {
                type: 'line',

                data: {
                    labels,
                    datasets: [{
                        data: attendance,
                        borderColor: colors.primary,
                        backgroundColor: 'rgba(98, 89, 232, .10)',
                        tension: .38,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: colors.primary
                    }]
                },

                options: {
                    ...baseOptions,
                    scales: {
                        ...baseOptions.scales,
                        y: {
                            ...baseOptions.scales.y,
                            ticks: {
                                ...baseOptions.scales.y.ticks,
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Payroll overview chart
            new Chart(document.getElementById('payroll-chart'), {
                type: 'bar',

                data: {
                    labels,
                    datasets: [{
                        data: payroll,
                        backgroundColor: colors.cyan,
                        borderRadius: 6,
                        maxBarThickness: 34
                    }]
                },

                options: {
                    ...baseOptions,

                    scales: {
                        ...baseOptions.scales,
                        y: {
                            ...baseOptions.scales.y,
                            ticks: {
                                ...baseOptions.scales.y.ticks,
                                callback: value =>
                                    new Intl.NumberFormat('id-ID', {
                                        notation: 'compact'
                                    }).format(value)
                            }
                        }
                    },

                    plugins: {
                        ...baseOptions.plugins,
                        tooltip: {
                            callbacks: {
                                label: item =>
                                    'Rp ' + new Intl.NumberFormat('id-ID')
                                    .format(item.parsed.y)
                            }
                        }
                    }
                }
            });
        })();
    </script>
@endpush
