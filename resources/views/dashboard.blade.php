@extends('layouts.app')

@section('title', 'PawID | Dashboard')

@section('content')
  <div class="container-fluid px-0">
    <div class="row mb-4 g-3"><div class="col"><div class="page-kicker">Overview</div><h1 class="page-title mb-1">Welcome back, {{ $user->name }}</h1><p class="text-muted mb-0">Here's how your pets are doing today.</p></div><div class="col-12 col-md-auto d-flex align-items-end"><a href="/register-pet" class="btn btn-brand"><i class="bi bi-plus-lg me-1"></i>Register Pet</a></div></div>
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3"><div class="card card-light p-3"><div class="metric-label">Registered Pets</div><div class="metric-value mt-1">{{ $petCount }}</div></div></div>
      <div class="col-6 col-md-3"><div class="card card-light p-3"><div class="metric-label">Vaccinations Given</div><div class="metric-value mt-1">{{ $vaccinationCount }}</div></div></div>
      <div class="col-6 col-md-3"><div class="card card-light p-3"><div class="metric-label">Deworming Records</div><div class="metric-value mt-1">{{ $dewormingCount }}</div></div></div>
      <div class="col-6 col-md-3"><div class="card card-light p-3"><div class="metric-label">Your Account</div><div class="small fw-semibold text-truncate mt-1">{{ $user->email }}</div><div class="record-meta">@if ($user->contact_number) {{ $user->contact_number }} @else No contact number @endif</div></div></div>
    </div>
    <div class="card card-light p-4">
      <h2 class="h6 mb-2">Your Pets</h2>
      @if ($pets->isEmpty())
        <div class="text-center py-5">
          <p class="text-muted mb-3">You have no pets registered yet. Create your first PawID profile to get started.</p>
          <a href="/register-pet" class="btn btn-brand"><i class="bi bi-plus-lg me-1"></i>Register your first pet</a>
        </div>
      @else
        <p class="record-meta mb-3">{{ $pets->count() }} {{ Str::plural('pet', $pets->count()) }} registered to your account.</p>
        <div class="row g-3">
          @foreach ($pets as $pet)
            <div class="col-12 col-md-6 col-lg-4">
              <a href="/pets/{{ $pet->id }}" class="text-decoration-none text-body">
                <div class="card card-light p-3 h-100">
                  <div class="d-flex align-items-center">
                    <span class="user-chip"><i class="bi bi-heart"></i></span>
                    <div class="ms-3 text-truncate" style="min-width:0">
                      <div class="fw-semibold">{{ $pet->name }}</div>
                      <div class="record-meta">{{ $pet->breed ?? 'Not specified' }} · {{ $pet->sex }}</div>
                      <div class="record-meta mt-1">{{ $pet->code }}</div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
@endsection
