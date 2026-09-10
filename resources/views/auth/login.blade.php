@extends('layouts.public')

@section('title', 'PawID | Sign In')

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

                <h1>Welcome back to your pet dashboard.</h1>
                <p>Track vaccinations, keep medical records current, and give every pet a secure QR profile in one place.</p>

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
                    <h2>Log in</h2>
                    <p>Access your pet care records and manage upcoming visits.</p>

                    <form method="POST" action="{{ route('login.store') }}" class="form-grid">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger py-2 mb-0" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $errors->first() }}
                            </div>
                        @endif

                        <div class="field-group">
                            <label for="email">Email address</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" required />
                        </div>

                        <div class="field-group">
                            <label for="password">Password</label>
                            <div class="password-wrap">
                                <input id="password" name="password" type="password" placeholder="Enter your password" required />
                                <button type="button" class="password-toggle" aria-label="Show password">Show</button>
                            </div>
                        </div>

                        <div class="form-row">
                            <label class="checkbox-wrap">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                <span>Remember me</span>
                            </label>
                            <a href="#" class="form-link">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary auth-submit">Sign In</button>
                    </form>

                    <p class="auth-extra">
                        Need an account? <a href="{{ route('register') }}">Create one</a>
                    </p>

                    <a href="{{ route('home') }}" class="auth-back-link">&larr; Back to PawID</a>
                </div>
            </div>
        </div>
    </section>
@endsection
