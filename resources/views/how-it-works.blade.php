@extends('layouts.public')

@section('title', 'PawID | How It Works')

@section('content')
    <section class="page-section" id="how-it-works">
        <div class="container">
            <div class="section-heading section-heading--center">
                <span class="eyebrow">How It Works</span>
                <h2>How PawID works</h2>
                <p>From registration to a scannable tag, your pet is protected in four simple steps.</p>
            </div>
            <div class="row g-4">
                @foreach ([
                        ['01', 'Register', 'Create your account and add your pet\'s basic details and photo.'],
                        ['02', 'Add Records', 'Fill in vaccinations, deworming, and medical history as they happen.'],
                        ['03', 'Get Your PawID', 'Generate your pet\'s unique QR code and attach it to their collar tag.'],
                        ['04', 'Scan & Access', 'Anyone who scans the code instantly sees how to help and who to call.'],
                    ] as $step)
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="step-item h-100">
                            <div class="step-index">{{ $step[0] }}</div>
                            <h3>{{ $step[1] }}</h3>
                            <p>{{ $step[2] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="page-actions">
                <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                <a href="{{ route('features') }}" class="btn btn-secondary">View Features</a>
            </div>
        </div>
    </section>
@endsection
