@extends('layouts.app')
@section('title', 'PawID | Staff Dashboard')
@section('content')
 <div class="container-fluid px-0">
    <div class="row mb-4 g-3">
      <div class="col"><div class="page-kicker">OCV Staff</div><h1 class="page-title mb-1">Welcome back, {{ $user->name }}</h1><p class="text-muted mb-0">Office of the City Veterinarian workspace.</p></div>
      <div class="col-12 col-md-auto d-flex align-items-end gap-2">
        <a href="{{ route('staff.users') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-people me-1"></i>User Management</a>
        <a href="{{ route('staff.pets.create') }}" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>Register Pet</a>
      </div>
    </div>
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3"><div class="card card-light p-3"><div class="metric-label">Registered Clients</div><div class="metric-value mt-1">{{ $userCount }}</div></div></div>
      <div class="col-6 col-md-3"><div class="card card-light p-3"><div class="metric-label">Registered Pets</div><div class="metric-value mt-1">{{ $petCount }}</div></div></div>
      <div class="col-6 col-md-3"><a href="{{ url('/vaccinations') }}" class="text-decoration-none"><div class="card card-light p-3"><div class="metric-label">Vaccination Records</div><div class="metric-value mt-1">{{ \App\Models\Vaccination::count() }}</div></div></a></div>
      <div class="col-6 col-md-3"><a href="{{ url('/deworming') }}" class="text-decoration-none"><div class="card card-light p-3"><div class="metric-label">Deworming Records</div><div class="metric-value mt-1">{{ \App\Models\DewormingRecord::count() }}</div></div></a></div>
    </div>
    <div class="row g-3">
      <div class="col-lg-6">
        <div class="card card-light p-4 h-100">
          <h2 class="h6 mb-3">Recent pet registrations</h2>
          @if ($recentPets->isEmpty())
            <p class="text-muted mb-0">No pets registered yet.</p>
          @else
            <ul class="list-unstyled mb-0">
              @foreach ($recentPets as $pet)
                <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                  <span><span class="record-name">{{ $pet->name }}</span><div class="record-meta">{{ $pet->owner_name ?? 'No owner' }} &middot; {{ $pet->species }}</div></span>
                  <a href="{{ route('pets.show', $pet) }}" class="btn btn-outline-secondary btn-sm">View</a>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card card-light p-4 h-100">
          <h2 class="h6 mb-3">Recent client registrations</h2>
          @if ($recentUsers->isEmpty())
            <p class="text-muted mb-0">No clients registered yet.</p>
          @else
            <ul class="list-unstyled mb-0">
              @foreach ($recentUsers as $client)
                <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                  <span><span class="record-name">{{ $client->name }}</span><div class="record-meta">{{ $client->email }}</div></span>
                  <a href="{{ route('staff.users.show', $client) }}" class="btn btn-outline-secondary btn-sm">Details</a>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>
 </div>
@endsection
