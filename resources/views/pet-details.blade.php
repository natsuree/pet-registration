@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
      <a href="/mypets" class="small text-decoration-none">&larr; Back to My Pets</a>
      <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bi bi-trash me-1"></i>Delete pet</button>
    </div>

    <div class="card card-light p-4 mt-3">
      <div class="d-flex align-items-center mb-3">
        @if ($pet->photo_path)
          <img src="{{ asset('storage/'.$pet->photo_path) }}" alt="Photo of {{ $pet->name }}" class="rounded"
            style="width:56px;height:56px;object-fit:cover;">
        @else
          <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:56px;height:56px"><i class="bi bi-heart"></i></div>
        @endif
        <div class="ms-3">
          <h1 class="h4 mb-0">{{ $pet->name }}</h1>
          <div class="small text-muted">{{ $pet->code }}</div>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-6 col-md-3"><div class="small text-muted">Species</div><div>{{ $pet->species ?? 'Not specified' }}</div></div>
        <div class="col-6 col-md-3"><div class="small text-muted">Breed</div><div>{{ $pet->breed ?? 'Not specified' }}</div></div>
        <div class="col-6 col-md-3"><div class="small text-muted">Sex</div><div>{{ $pet->sex }}</div></div>
        <div class="col-6 col-md-3"><div class="small text-muted">Date of Birth</div><div>{{ $pet->date_of_birth?->format('M d, Y') ?? 'Not specified' }}</div></div>
        <div class="col-6 col-md-3"><div class="small text-muted">Color</div><div>{{ $pet->color ?? 'Not specified' }}</div></div>
        <div class="col-6 col-md-3"><div class="small text-muted">Microchip</div><div>{{ $pet->microchip ?? 'None' }}</div></div>
      </div>
    </div>

    <div class="card card-light p-4 mt-3">
      <h2 class="h6 mb-3">Owner information</h2>
      <div class="row g-3">
        <div class="col-6 col-md-3"><div class="small text-muted">Owner name</div><div>{{ $pet->owner_name ?? 'Not specified' }}</div></div>
        <div class="col-6 col-md-3"><div class="small text-muted">Email</div><div>{{ $pet->owner_email ?? 'Not specified' }}</div></div>
        <div class="col-6 col-md-3"><div class="small text-muted">Contact number</div><div>{{ $pet->owner_number ?? 'Not specified' }}</div></div>
      </div>
    </div>

    <div class="row g-3 mt-1">
      <div class="col-md-6">
        <div class="card card-light p-3 h-100">
          <h2 class="h6 mb-2">Vaccinations</h2>
          @if ($pet->vaccinations->isEmpty())
            <div class="small text-muted">No vaccination records.</div>
          @else
            <ul class="list-unstyled mb-0 small">
              @foreach ($pet->vaccinations as $record)
                <li class="d-flex justify-content-between border-bottom py-2"><span>{{ $record->vaccine }}</span><span class="text-muted">{{ $record->administered_at->format('M d, Y') }}</span></li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-light p-3 h-100">
          <h2 class="h6 mb-2">Deworming</h2>
          @if ($pet->dewormingRecords->isEmpty())
            <div class="small text-muted">No deworming records.</div>
          @else
            <ul class="list-unstyled mb-0 small">
              @foreach ($pet->dewormingRecords as $record)
                <li class="d-flex justify-content-between border-bottom py-2"><span>{{ $record->product }}</span><span class="text-muted">{{ $record->administered_at->format('M d, Y') }}</span></li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete {{ $pet->name }}?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        This will permanently remove <strong>{{ $pet->name }}</strong> ({{ $pet->code }}) and its vaccination and deworming records. This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <form method="POST" action="/pets/{{ $pet->id }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Yes, delete pet</button>
        </form>
      </div>
    </div>
  </div>
</div>
