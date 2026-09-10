@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-end gap-3 mb-4">
        <div><div class="page-kicker">Health records</div><h1 class="page-title mb-1">Deworming</h1><p class="text-muted mb-0">Track parasite prevention and upcoming treatments for every pet.</p></div>
        @if ($pets->isNotEmpty())<button class="btn btn-brand btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addDeworming"><i class="bi bi-plus-lg me-1"></i>Add record</button>@else<a href="/register-pet" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>Register a pet</a>@endif
    </div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger mb-4">Please review the record details and try again.</div>@endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3"><div class="card card-light p-3 h-100"><div class="metric-label">Treatment records</div><div class="metric-value mt-1">{{ $stats['total'] }}</div><div class="record-meta mt-1">Across registered pets</div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="card card-light p-3 h-100"><div class="metric-label">Up to date</div><div class="metric-value mt-1">{{ $stats['current'] }}</div><div class="record-meta mt-1">No action needed</div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="card card-light p-3 h-100"><div class="metric-label">Due soon</div><div class="metric-value mt-1">{{ $stats['dueSoon'] }}</div><div class="record-meta mt-1">Within the next 30 days</div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="card card-light p-3 h-100"><div class="metric-label">Overdue</div><div class="metric-value text-danger mt-1">{{ $stats['overdue'] }}</div><div class="record-meta mt-1">Needs attention</div></div></div>
    </div>

    <section class="card card-light overflow-hidden">
        <div class="d-flex justify-content-between align-items-center gap-2 p-3 p-md-4 border-bottom"><div><h2 class="h6 mb-1 fw-bold">Treatment history</h2><p class="record-meta mb-0">{{ $records->count() }} {{ Str::plural('record', $records->count()) }} saved</p></div></div>
        <div class="table-responsive"><table class="table mb-0"><thead><tr><th>Pet</th><th>Product</th><th>Date given</th><th>Next due</th><th>Weight</th><th>Status</th></tr></thead><tbody>
            @forelse ($records as $record)
                @php $status = !$record->next_due_at ? ['current', 'No due date', 'bi-check2-circle'] : ($record->next_due_at->isBefore(today()) ? ['overdue', 'Overdue', 'bi-exclamation-circle'] : ($record->next_due_at->lte(today()->addDays(30)) ? ['due', 'Due soon', 'bi-clock'] : ['current', 'Up to date', 'bi-check2-circle'])); @endphp
                <tr><td><div class="record-name">{{ $record->pet->name }}</div><div class="record-meta">{{ $record->pet->breed ?? $record->pet->species }}</div></td><td>{{ $record->product }}</td><td>{{ $record->administered_at->format('M j, Y') }}</td><td>{{ $record->next_due_at?->format('M j, Y') ?? 'Not scheduled' }}</td><td>{{ $record->weight_kg ? $record->weight_kg.' kg' : 'Not recorded' }}</td><td><span class="status-badge status-{{ $status[0] }}"><i class="bi {{ $status[2] }}"></i>{{ $status[1] }}</span></td></tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-5">No deworming records yet.</td></tr>
            @endforelse
        </tbody></table></div>
    </section>

    @if ($pets->isNotEmpty())
        <div class="modal fade" id="addDeworming" tabindex="-1" aria-labelledby="addDewormingTitle" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><form method="POST" action="/deworming">@csrf<div class="modal-header"><h2 class="modal-title fs-5" id="addDewormingTitle">Add deworming record</h2><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row g-3"><div class="col-md-6"><label class="form-label">Pet</label><select name="pet_id" class="form-select" required><option value="">Select pet</option>@foreach ($pets as $pet)<option value="{{ $pet->id }}">{{ $pet->name }} ({{ $pet->code }})</option>@endforeach</select></div><div class="col-md-6"><label class="form-label">Product</label><input name="product" class="form-control" required></div><div class="col-md-6"><label class="form-label">Date given</label><input name="administered_at" type="date" class="form-control" required></div><div class="col-md-6"><label class="form-label">Next due</label><input name="next_due_at" type="date" class="form-control"></div><div class="col-md-6"><label class="form-label">Weight (kg)</label><input name="weight_kg" type="number" min="0" step="0.01" class="form-control"></div><div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3"></textarea></div></div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-brand" type="submit">Save record</button></div></form></div></div></div>
    @endif
@endsection
