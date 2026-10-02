<x-guest-layout>
    <div class="auth-kicker">SECURITY CHECK</div>
    <h2 class="auth-title">Confirm your password</h2>
    <p class="auth-subtitle">For your security, please verify your password to continue.</p>
    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">@csrf
        <div class="mb-4"><label class="form-label" for="password">Password</label><input type="password" id="password"
                name="password" class="form-control" required autocomplete="current-password"><x-input-error
                :messages="$errors->get('password')" /></div>
        <button class="btn btn-primary w-100 auth-submit" type="submit">Confirm password</button>
    </form>
</x-guest-layout>
