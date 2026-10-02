<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="PeopleOps HR management dashboard">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'PeopleOps') }}</title>

    <link rel="icon" href="{{ asset('mazer/assets/compiled/svg/favicon.svg') }}" type="image/svg+xml">

    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app.css') }}">

    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app-dark.css') }}">

    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/bootstrap-icons/font/bootstrap-icons.css') }}">

    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">

    @stack('styles')
</head>

<body>

    <script src="{{ asset('mazer/assets/static/js/initTheme.js') }}"></script>

    @php($isHr = auth()->user()->isHr())

    <div id="app">

        <!-- SIDEBAR -->
        <div id="sidebar">

            <div class="sidebar-wrapper active">

                <!-- Sidebar header -->
                <div class="sidebar-header position-relative">

                    <div class="d-flex align-items-center justify-content-between">

                        <a class="brand-lockup" href="{{ route('dashboard') }}" aria-label="PeopleOps dashboard">
                            <span>
                                people<span class="brand-accent">ops</span>
                                <small>HR WORKSPACE</small>
                            </span>
                        </a>

                        <!-- Mobile sidebar close button -->
                        <a href="#"
                            class="sidebar-hide d-inline-flex d-xl-none align-items-center justify-content-center p-2"
                            aria-label="Close navigation">
                            <i class="bi bi-x-lg d-flex lh-1 fs-4"></i>
                        </a>

                    </div>

                </div>

                <!-- Sidebar navigation -->
                <div class="sidebar-menu">

                    <ul class="menu">

                        <li class="sidebar-title">
                            Workspace
                        </li>

                        <!-- Overview -->
                        <li class="sidebar-item {{ request()->routeIs('dashboard*') ? 'active' : '' }}">

                            <a href="{{ route('dashboard') }}" class="sidebar-link">
                                <i class="bi bi-grid-1x2"></i>
                                <span>Overview</span>
                            </a>

                        </li>

                        <!-- Tasks -->
                        <li class="sidebar-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">

                            <a href="{{ route('tasks.index') }}" class="sidebar-link">
                                <i class="bi bi-check2-square"></i>
                                <span>Tasks</span>
                            </a>

                        </li>

                        <!-- Attendance -->
                        <li class="sidebar-item {{ request()->routeIs('presences.*') ? 'active' : '' }}">

                            <a href="{{ route('presences.index') }}" class="sidebar-link">
                                <i class="bi bi-calendar2-check"></i>
                                <span>Attendance</span>
                            </a>

                        </li>

                        <!-- Leave requests -->
                        <li class="sidebar-item {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">

                            <a href="{{ route('leave-requests.index') }}" class="sidebar-link">
                                <i class="bi bi-calendar2-heart"></i>
                                <span>Leave requests</span>
                            </a>

                        </li>

                        <!-- Payroll -->
                        <li class="sidebar-item {{ request()->routeIs('payrolls.*') ? 'active' : '' }}">

                            <a href="{{ route('payrolls.index') }}" class="sidebar-link">
                                <i class="bi bi-receipt"></i>

                                <span>
                                    {{ $isHr ? 'Payroll' : 'My payslips' }}
                                </span>
                            </a>

                        </li>

                        @if ($isHr)
                            <li class="sidebar-title">
                                People &amp; organization
                            </li>

                            <!-- Employees -->
                            <li class="sidebar-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">

                                <a href="{{ route('employees.index') }}" class="sidebar-link">
                                    <i class="bi bi-person-lines-fill"></i>
                                    <span>Employees</span>
                                </a>

                            </li>

                            <!-- Departments -->
                            <li class="sidebar-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">

                                <a href="{{ route('departments.index') }}" class="sidebar-link">
                                    <i class="bi bi-diagram-3"></i>
                                    <span>Departments</span>
                                </a>

                            </li>

                            <!-- Roles -->
                            <li class="sidebar-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">

                                <a href="{{ route('roles.index') }}" class="sidebar-link">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Roles</span>
                                </a>

                            </li>
                        @endif

                        <li class="sidebar-title">
                            Account
                        </li>

                        <!-- Profile -->
                        <li class="sidebar-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">

                            <a href="{{ route('profile.edit') }}" class="sidebar-link">
                                <i class="bi bi-gear"></i>
                                <span>Profile &amp; security</span>
                            </a>

                        </li>

                        <!-- Sign out -->
                        <li class="sidebar-item">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button class="sidebar-link sidebar-logout w-100" type="submit">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Sign out</span>
                                </button>

                            </form>

                        </li>

                    </ul>

                </div>

            </div>

        </div>

        <!-- MAIN CONTENT -->
        <div id="main">

            <!-- Workspace header -->
            <header class="workspace-header mb-4 d-flex align-items-center justify-content-between gap-3 flex-wrap">

                <div class="d-flex align-items-center gap-3">

                    <div>

                        <div class="workspace-eyebrow">
                            PEOPLE OPERATIONS
                        </div>

                        <div class="workspace-greeting">
                            {{ now()->format('l, j F Y') }}
                        </div>

                    </div>

                </div>

                <!-- User information -->
                <div class="d-flex align-items-center gap-3">

                    <span class="workspace-pill d-none d-sm-inline">
                        {{ $isHr ? 'HR administrator' : 'Team member' }}
                    </span>

                    <a class="user-chip" href="{{ route('profile.edit') }}">

                        <span class="avatar-initials">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>

                        <span class="d-none d-sm-block">

                            <strong>
                                {{ auth()->user()->name }}
                            </strong>

                            <small>
                                View profile
                            </small>

                        </span>

                    </a>

                </div>

            </header>

            <!-- Success messages -->
            @if (session('success'))
                <div class="alert alert-success shadow-sm" role="status">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Validation errors -->
            @if ($errors->any())

                <div class="alert alert-danger shadow-sm" role="alert">

                    <strong>
                        Please review the following:
                    </strong>

                    <ul class="mb-0 mt-1">

                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach

                    </ul>

                </div>

            @endif

            <!-- Page content -->
            @yield('content')

            <!-- Footer -->
            <footer class="workspace-footer">

                <span>
                    © {{ now()->year }} PeopleOps - Aliffian Cahya's Portfolio Demonstration
                </span>

                <span>
                    Interface powered by

                    <a href="https://github.com/zuramai/mazer" target="_blank" rel="noopener noreferrer">
                        Mazer
                    </a>
                </span>

            </footer>

        </div>

        <!-- FLOATING MOBILE MENU BUTTON -->
        <a href="#"
            class="burger-btn btn btn-primary position-fixed bottom-0 end-0 m-3 d-inline-flex d-xl-none align-items-center justify-content-center rounded-circle shadow-lg p-3 z-3"
            aria-label="Open navigation" aria-controls="sidebar" title="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="d-block flex-shrink-0" aria-hidden="true">
                <path d="M4 6h16"></path>
                <path d="M4 12h16"></path>
                <path d="M4 18h16"></path>
            </svg>
        </a>

    </div>

    <!-- Mazer scripts -->
    <script src="{{ asset('mazer/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <script src="{{ asset('mazer/assets/compiled/js/app.js') }}"></script>

    @stack('scripts')

</body>

</html>
