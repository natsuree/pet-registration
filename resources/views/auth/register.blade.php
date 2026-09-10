@extends('layouts.public')

@section('title', 'PawID | Create Account')

@section('content')
    <section class="auth-shell">
        <div class="auth-grid">
            <div class="auth-panel auth-panel--brand">
                <div class="auth-brand">
                    <span class="brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 32 32" role="img" aria-hidden="true">
                            <path d="M9.5 7.2c1.8 0 3.2-1.4 3.2-3.2S11.3 0 9.5 0 6.3 1.4 6.3 3.2s1.5 3.2 3.2 3.2Zm13 0c1.8 0 3.2-1.4 3.2-3.2S24.3 0 22.5 0s-3.2 1.4-3.2 3.2 1.5 3.2 3.2 3.2ZM4.6 16.1c1.8 0 3.2-1.4 3.2-3.2s-1.4-3.2-3.2-3.2S1.4 11.1 1.4 13s1.5 3.1 3.2 3.1Zm22.8 0c1.8 0 3.2-1.4 3.2-3.2s-1.4-3.2-3.2-3.2-3.2 1.4-3.2 3.2 1.4 3.2 3.2 3.2ZM16 11.6c2.3 0 4.1 1.9 4.1 4.1v6.6c0 2.3-1.8 4.1-4.1 4.1s-4.1-1.8-4.1-4.1v-6.6c0-2.2 1.8-4.1 4.1-4.1Zm2.1-8.7c1.8 0 3.2-1.4 3.2-3.2S19.9 0 18.1 0s-3.2 1.4-3.2 3.2 1.4 3.2 3.2 3.2Zm-10.6 6.8c1.6 0 3-1.3 3-3s-1.3-3-3-3-3 1.3-3 3 1.4 3 3 3Zm18.9 0c1.6 0 3-1.3 3-3s-1.3-3-3-3-3 1.3-3 3 1.4 3 3 3Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="brand-text">PawID</span>
                </div>

                <h1>Build a complete care profile for every pet.</h1>
                <p>Register your account to start tracking pet details, vaccines, medical updates, and QR identification from one secure home.</p>

                <div class="auth-visual" aria-hidden="true">
                    <div class="paw-illustration">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>

            <div class="auth-panel">
                <div class="auth-form-shell">
                    <h2>Create account</h2>
                    <p>Join PawID to keep all your pet records organized and easy to share.</p>

                    <form method="POST" action="{{ route('register.store') }}" class="form-grid">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger py-2 mb-0" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $errors->first() }}
                            </div>
                        @endif

                        <div class="field-group">
                            <label for="full_name">Full name</label>
                            <input id="full_name" name="name" type="text" value="{{ old('name') }}" placeholder="Jordan Smith" required />
                        </div>

                        <div class="field-group">
                            <label for="register_email">Email address</label>
                            <input id="register_email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" required />
                        </div>

                        <div class="field-group">
                            <label for="contact_number">Contact number</label>
                            <input id="contact_number" name="contact_number" type="tel" value="{{ old('contact_number') }}" placeholder="09XX XXX XXXX" required />
                        </div>

                        <div class="field-group">
                            <label for="register_password">Password</label>
                            <div class="password-wrap">
                                <input id="register_password" name="password" type="password" placeholder="Create a password (min. 8 characters)" required />
                                <button type="button" class="password-toggle" aria-label="Show password">Show</button>
                            </div>
                        </div>

                        <div class="field-group">
                            <label for="confirm_password">Confirm password</label>
                            <input id="confirm_password" name="password_confirmation" type="password" placeholder="Re-enter your password" required />
                        </div>

                        <button type="submit" class="btn btn-primary auth-submit">Create Account</button>
                    </form>

                    <p class="auth-extra">
                        Already have an account? <a href="{{ route('login') }}">Sign in</a>
                    </p>

                    <a href="{{ route('home') }}" class="auth-back-link">&larr; Back to PawID</a>
                </div>
            </div>
        </div>
    </section>
@endsection
