<x-guest-layout>
    <div class="auth-kicker">WELCOME BACK</div>
    <h2 class="auth-title">Sign in to PeopleOps</h2>
    <p class="auth-subtitle">Access your workspace with your employee credentials.</p>
    <x-auth-session-status class="auth-alert" :status="session('status')" />
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <div class="mb-4"><label class="form-label" for="email">Work email</label><input type="email"
                id="email" name="email" class="form-control" value="{{ old('email') }}"
                placeholder="you@company.com" autocomplete="username" autofocus required></div>
        <div class="mb-3">
            <div class="d-flex justify-content-between gap-3 align-items-center"><label class="form-label"
                    for="password">Password</label>
                @if (Route::has('password.request'))
                    <a class="auth-forgot" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div><input type="password" id="password" name="password" class="form-control"
                placeholder="Enter your password" autocomplete="current-password" required>
        </div>
        <label class="auth-remember mb-4"><input type="checkbox" name="remember" value="1"
                {{ old('remember') ? 'checked' : '' }}> Keep me signed in</label>
        <button type="submit" class="btn btn-primary w-100 auth-submit">Sign in</button>
    </form>

    @if (app()->environment('local'))
        <div class="auth-demo">
            <div class="auth-demo-label">TRY THE LOCAL DEMO
            </div>

            <div class="d-flex flex-wrap gap-2 mt-2">
                <button type="button" class="auth-demo-chip auth-demo-fill" data-email="hr@demo.test">
                    HR: <strong>hr@demo.test</strong>
                </button>

                <button type="button" class="auth-demo-chip auth-demo-fill" data-email="alex@demo.test">
                    Employee: <strong>alex@demo.test</strong>
                </button>
            </div>

            <p class="mb-0 mt-2">
                Click an account to autofill your credentials.
            </p>
        </div>

        <script>
            document.querySelectorAll('.auth-demo-fill').forEach(button => {
                button.addEventListener('click', function() {
                    const email = this.dataset.email;
                    const password = {{ Illuminate\Support\Js::from(env('DEMO_PASSWORD', 'Password123!')) }};

                    document.getElementById('email').value = email;
                    document.getElementById('password').value = password;

                    document.getElementById('email').focus();
                });
            });
        </script>
    @endif

</x-guest-layout>
