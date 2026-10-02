<x-guest-layout>
    <div class="auth-kicker">ACCOUNT RECOVERY</div>
    <h2 class="auth-title">Set a new password</h2>
    <p class="auth-subtitle">Use a unique password with at least eight characters.</p>
    <form method="POST" action="{{ route('password.store') }}" class="auth-form">@csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="mb-3"><label class="form-label" for="email">Email</label><input class="form-control"
                id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                autocomplete="username"><x-input-error :messages="$errors->get('email')" /></div>
        <div class="mb-3"><label class="form-label" for="password">New password</label><input class="form-control"
                id="password" type="password" name="password" required autocomplete="new-password"><x-input-error
                :messages="$errors->get('password')" /></div>
        <div class="mb-4"><label class="form-label" for="password_confirmation">Confirm password</label><input
                class="form-control" id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password"></div>
        <button class="btn btn-primary w-100 auth-submit" type="submit">Reset password</button>
    </form>
</x-guest-layout>
