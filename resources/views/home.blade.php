@extends('layouts.public')

@section('title', 'PawID | QR-Based Pet Registration and Medical Record System')

@section('content')
    {{-- Hero --}}
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 hero-copy">
                    <span class="eyebrow">QR-Based Pet Registration &amp; Medical Records</span>
                    <h1>Your pet's identity, always within reach.</h1>
                    <p>Register your pet, keep their medical records organized, and give them a digital identity with PawID.
                    </p>
                    <div class="hero-actions">
                        <a href="/register" class="btn btn-primary btn-primary--large">
                            <i class="bi bi-qr-code me-2"></i>Register Your Pet
                        </a>
                        <a href="#how-it-works" class="btn btn-secondary btn-primary--large">Learn More</a>
                    </div>
                </div>

                <div class="col-lg-6 hero-visual">
                    <div class="pet-card">
                        <div class="pet-card-top">
                            <div class="pet-photo" role="img" aria-label="Pet photo placeholder">
                                <span class="pet-photo-badge">Photo</span>
                            </div>
                            <div class="pet-meta">
                                <h3>Bruno</h3>
                                <p>Dog &middot; Male &middot; 2 years old</p>
                            </div>
                        </div>
                        <div class="pet-qr-wrap">
                            <div class="pet-qr" role="img" aria-label="QR code placeholder">
                                @for ($i = 1; $i <= 64; $i++)
                                    <span></span>
                                @endfor
                            </div>
                        </div>
                        <div class="pet-card-footer">
                            <span><i class="bi bi-qr-code me-2"></i>PawID QR</span>
                            <span>Verified Profile</span>
                        </div>
                    </div>

                    <div class="floating-status">
                        <span class="status-icon"><i class="bi bi-shield-check"></i></span>
                        <div>
                            <strong>Records up to date</strong>
                            <p>Vaccination &middot; Deworming &middot; Medical</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-heading section-heading--center">
                <h2>Everything your pet needs, in one profile</h2>
                <p>PawID keeps every record organized so veterinarians and finders can help your pet faster.</p>
            </div>
            <div class="row g-4 feature-grid">
                @foreach ([
                        ['bi-person-vcard', 'Pet Registration', 'Create a complete identity for your pet with details, photos, and a unique pet ID.'],
                        ['bi-id-card', 'Digital Pet Profile', 'Access your pet\'s full profile anytime from any device, no paperwork required.'],
                        ['bi-shield-plus', 'Vaccination Records', 'Track vaccine schedules and keep shot histories current and easy to share.'],
                        ['bi-capsule', 'Deworming Records', 'Log every deworming treatment so you never miss the next dose.'],
                        ['bi-clipboard2-pulse', 'Medical Records', 'Store diagnoses, treatments, and vet notes in one secure timeline.'],
                        ['bi-qr-code-scan', 'QR Code Identification', 'A single scan of your pet\'s QR tag reveals who to contact and what they need.'],
                    ] as $feature)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="feature-card h-100">
                            <div class="feature-icon"><i class="bi {{ $feature[0] }}"></i></div>
                            <h3>{{ $feature[1] }}</h3>
                            <p>{{ $feature[2] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="process-section" id="how-it-works">
        <div class="container">
            <div class="section-heading section-heading--center">
                <h2>How PawID works</h2>
                <p>From registration to a scannable tag, your pet is protected in four simple steps.</p>
            </div>
            <div class="row g-4 process-grid">
                @foreach ([
                        ['01', 'Register', 'Create your account and add your pet\'s basic details and photo.'],
                        ['02', 'Add Records', 'Fill in vaccinations, deworming, and medical history as they happen.'],
                        ['03', 'Get Your PawID', 'Generate your pet\'s unique QR code and attach it to their collar tag.'],
                        ['04', 'Scan &amp; Access', 'Anyone who scans the code instantly sees how to help and who to call.'],
                    ] as $step)
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="step-item h-100">
                            <div class="step-index">{{ $step[0] }}</div>
                            <h3>{!! $step[1] !!}</h3>
                            <p>{!! $step[2] !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- QR section --}}
    <section class="qr-section">
        <div class="container">
            <div class="row align-items-center g-5 qr-grid">
                <div class="col-lg-6 qr-copy">
                    <span class="eyebrow">Built-in QR identification</span>
                    <h2>One scan. Important information at your fingertips.</h2>
                    <p>Every PawID pet gets a unique QR tag. When someone scans it, they see the pet's profile, medical
                        needs, and your contact details — instantly and securely.</p>
                    <div class="hero-actions">
                        <a href="/register" class="btn btn-primary">Create your pet's QR tag</a>
                    </div>
                </div>
                <div class="col-lg-6 qr-panel">
                    <div class="qr-card">
                        <div class="mini-pet-card">
                            <div class="mini-avatar" role="img" aria-label="Pet photo placeholder"></div>
                            <div>
                                <strong>Bruno</strong>
                                <span>Dog &middot; Male &middot; 2 years old</span>
                            </div>
                        </div>
                        <div class="qr-visual" role="img" aria-label="QR code placeholder">
                            @for ($i = 1; $i <= 64; $i++)
                                <span></span>
                            @endfor
                        </div>
                        <p><i class="bi bi-qr-code-scan me-2"></i>Scan to view Bruno's PawID profile</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="cta-section">
        <div class="container">
            <div class="cta-shell">
                <div>
                    <h2>Give your pet a digital identity.</h2>
                    <p>Registration takes minutes. The peace of mind lasts a lifetime.</p>
                </div>
                <div class="hero-actions m-0">
                    <a href="/register" class="btn btn-primary">Sign Up Free</a>
                    <a href="/login" class="btn btn-secondary">Sign In</a>
                </div>
            </div>
        </div>
    </section>
@endsection