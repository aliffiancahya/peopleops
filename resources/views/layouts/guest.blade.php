<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in - {{ config('app.name', 'PeopleOps HR') }}</title>
    <link rel="icon" href="{{ asset('mazer/assets/compiled/svg/favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
    <div class="auth-shell">
        <aside class="auth-showcase" aria-label="PeopleOps product overview">
            <div class="auth-brand"><span>people<span>ops</span></span></div>
            <div class="auth-story">
                <h1>Make room for<br><em>better work</em></h1>
                <p>One calm place to manage your team, track attendance, approve time off and keep everyone moving.</p>
                <div class="auth-showcase-card" aria-hidden="true">
                    <div class="auth-card-top"><span>TEAM OVERVIEW</span><span class="auth-live-dot">&nbsp;LIVE</span>
                    </div>
                    <div class="auth-fake-chart"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
                    <div class="auth-card-bottom"><span>Connected people</span><strong>One workspace.</strong></div>
                </div>
            </div>
            <div class="auth-bottom">ALIFFIAN CAHYA'S PORTFOLIO DEMONSTRATION</div>
        </aside>
        <main class="auth-content">
            <div class="auth-form-panel">
                <div class="auth-mobile-brand">people<span>ops</span></div>
                {{ $slot }}
                <p class="auth-legal">A demonstration project. Do not enter real employee or payroll information in a
                    public demo.</p>
            </div>
        </main>
    </div>
</body>

</html>
