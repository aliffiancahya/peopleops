@extends('layouts.dashboard')
@section('title', 'Profile & security')
@section('content')
    <div class="page-heading mb-4">
        <div class="eyebrow">ACCOUNT</div>
        <h1>Profile &amp; security</h1>
        <p class="text-muted">Update your login details and password.</p>
    </div>
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success">Profile updated successfully.</div>
    @endif
    @if (session('status') === 'password-updated')
        <div class="alert alert-success">Password changed successfully.</div>
    @endif
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <section class="card surface-card h-100">
                <div class="card-body">
                    <h3>Account details</h3>
                    <p class="text-muted small">Your name and email also update your linked employee profile.</p>
                    <form method="POST" action="{{ route('profile.update') }}" class="mt-4">@csrf @method('PATCH')
                        <div class="mb-3"><label class="form-label" for="name">Full name</label><input
                                class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}"
                                required maxlength="255"><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
                        <div class="mb-3"><label class="form-label" for="email">Email</label><input
                                class="form-control" type="email" id="email" name="email"
                                value="{{ old('email', $user->email) }}" required><x-input-error :messages="$errors->get('email')"
                                class="mt-2" /></div>
                        <button type="submit" class="btn btn-primary">Save profile</button>
                    </form>
                </div>
            </section>
        </div>
        <div class="col-12 col-lg-6">
            <section class="card surface-card h-100">
                <div class="card-body">
                    <h3>Change password</h3>
                    <p class="text-muted small">Choose a unique password for your portal account.</p>
                    <form method="POST" action="{{ route('password.update') }}" class="mt-4">@csrf @method('PUT')
                        <div class="mb-3"><label class="form-label" for="current_password">Current password</label><input
                                class="form-control" type="password" name="current_password" id="current_password"
                                autocomplete="current-password" required><x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>
                        <div class="mb-3"><label class="form-label" for="password">New password</label><input
                                class="form-control" type="password" name="password" id="password"
                                autocomplete="new-password" required><x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>
                        <div class="mb-3"><label class="form-label" for="password_confirmation">Confirm
                                password</label><input class="form-control" type="password" name="password_confirmation"
                                id="password_confirmation" autocomplete="new-password" required></div>
                        <button type="submit" class="btn btn-primary">Update password</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <div class="card surface-card mt-4">
        <div class="card-body">
            <h4>Close portal account</h4>
            <p class="text-muted small">This deletes your login, not your HR employment or payroll history. HR can restore
                portal access later.</p>
            <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse"
                data-bs-target="#delete-account-form" aria-expanded="false" aria-controls="delete-account-form">Delete my
                login</button>
            <div class="collapse mt-3" id="delete-account-form">
                <form method="POST" action="{{ route('profile.destroy') }}">@csrf @method('DELETE')<div class="mb-3">
                        <label class="form-label" for="delete-password">Confirm your password</label><input type="password"
                            class="form-control" name="password" id="delete-password" required
                            autocomplete="current-password"><x-input-error :messages="$errors->userDeletion->get('password')" /></div><button type="submit"
                        onclick="return confirm('Permanently delete your portal login?')" class="btn btn-danger">Confirm
                        deletion</button></form>
            </div>
        </div>
    </div>
@endsection
