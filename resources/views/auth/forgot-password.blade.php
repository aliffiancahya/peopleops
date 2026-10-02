<x-guest-layout>
    <div class="auth-kicker">ACCOUNT RECOVERY</div>
    <h2 class="auth-title">Reset your password</h2>
    <p class="auth-subtitle">We'll email you a link if your account exists.</p>
    <x-auth-session-status class="auth-alert" :status="session('status')" />
    <form method="POST" action="{{ route('password.email') }}" class="auth-form">@csrf
        <div class="mb-4"><label class="form-label" for="email">Work email</label><input class="form-control"
                type="email" id="email" name="email" value="{{ old('email') }}" required
                autofocus><x-input-error :messages="$errors->get('email')" /></div>
        <button class="btn btn-primary w-100 auth-submit" type="submit">Send reset link</button>
    </form>
    <p class="mt-4"><a href="{{ route('login') }}"><i class="bi bi-arrow-left me-2"></i>Back to sign in</a></p>
</x-guest-layout>
