<x-guest-layout>
    <div class="auth-kicker">ACCOUNT VERIFICATION</div>
    <h2 class="auth-title">Verify your email</h2>
    <p class="auth-subtitle">Check your inbox for a verification link before continuing.</p>
    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success">A new verification link has been sent to your email.</div>
    @endif
    <form method="POST" action="{{ route('verification.send') }}" class="auth-form">@csrf<button
            class="btn btn-primary w-100 auth-submit" type="submit">Resend verification email</button></form>
    <form method="POST" action="{{ route('logout') }}" class="mt-3">@csrf<button type="submit"
            class="btn btn-link">Sign out</button></form>
</x-guest-layout>
