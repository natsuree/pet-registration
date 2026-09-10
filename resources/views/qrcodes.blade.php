@extends('layouts.app')

@section('content')
    {{-- Controller contract: $pets is an iterable collection (or null); --}}
    {{-- $qrCodesGenerated and $publicProfiles are optional int/null metrics. --}}
    @php
        $pets = $pets ?? collect();
        $registeredCount = $pets->count();
        $qrCodesGenerated = $qrCodesGenerated ?? 0;
        $publicProfiles = $publicProfiles ?? 0;
    @endphp

    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-end gap-3 mb-4">
        <div>
            <div class="page-kicker">Pet identity</div>
            <h1 class="page-title mb-1">QR Codes</h1>
            <p class="text-muted mb-0">Registered pets will be available for QR code generation.</p>
        </div>
        <a href="{{ route('pets.create') }}" class="btn btn-brand btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Register a pet
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-light p-3 h-100">
                <div class="metric-label">Registered pets</div>
                <div class="metric-value mt-1">{{ $registeredCount }}</div>
                <div class="record-meta mt-1">Available for a QR profile</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-light p-3 h-100">
                <div class="metric-label">QR codes generated</div>
                <div class="metric-value mt-1">{{ $qrCodesGenerated }}</div>
                <div class="record-meta mt-1">No codes issued yet</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-light p-3 h-100">
                <div class="metric-label">Public profiles</div>
                <div class="metric-value mt-1">{{ $publicProfiles }}</div>
                <div class="record-meta mt-1">No profiles published</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-light p-3 h-100">
                <div class="metric-label">Ready to generate</div>
                <div class="metric-value mt-1">{{ $registeredCount }}</div>
                <div class="record-meta mt-1">Pets with an ID</div>
            </div>
        </div>
    </div>

    <section class="card card-light overflow-hidden">
        <div class="p-3 p-md-4 border-bottom">
            <h2 class="h6 mb-1 fw-bold">Pet QR setup</h2>
            <p class="record-meta mb-0">Generation will be available for registered pets.</p>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Pet</th>
                        <th>Pet ID</th>
                        <th>QR status</th>
                    </tr>
                </thead>
                <tbody>@forelse ($pets as $pet)
                    <tr>
                        <td>
                            <div class="record-name">{{ $pet->name }}</div>
                            <div class="record-meta">{{ $pet->breed ?? $pet->species ?? '' }}</div>
                        </td>
                        <td>{{ $pet->code ?? 'N/A' }}</td>
                        <td class="text-nowrap">
                            <button type="button" class="btn btn-brand btn-sm"
                                data-bs-toggle="modal" data-bs-target="#qrPreviewModal"
                                data-preview-url="{{ route('qrcodes.preview', $pet) }}"
                                data-download-url="{{ route('qrcodes.download', $pet) }}"
                                data-pet-name="{{ $pet->name }}"
                                data-pet-code="{{ $pet->code ?? 'N/A' }}">
                                <i class="bi bi-qr-code me-1"></i>Preview QR
                            </button>
                            <a href="{{ route('qrcodes.download', $pet) }}" class="btn btn-outline-secondary btn-sm"
                                title="Download without preview">
                                <i class="bi bi-download"></i>
                            </a>
                        </td>
                </tr>@empty<tr>
                        <td colspan="3" class="text-center text-muted py-5">No registered pets are available for QR setup.
                        </td>
                    </tr>@endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="modal fade" id="qrPreviewModal" tabindex="-1" aria-labelledby="qrPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <div class="page-kicker">QR preview</div>
                        <h5 class="modal-title fw-bold" id="qrPreviewModalLabel">Pet QR Code</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="d-flex justify-content-center">
                        <div class="qr-preview" id="qrPreviewFrame" style="width: 260px;">
                            <div class="spinner-border text-secondary" id="qrPreviewSpinner" role="status"
                                aria-hidden="true"></div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="fw-bold" id="qrPreviewPetName">&nbsp;</div>
                        <div class="record-meta" id="qrPreviewPetCode">&nbsp;</div>
                    </div>
                    <p class="record-meta mt-2 mb-0">Scanning opens this pet's public profile page.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
                    <a href="#" class="btn btn-brand" id="qrPreviewDownload" download>
                        <i class="bi bi-download me-1"></i>Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('qrPreviewModal');
            var frame = document.getElementById('qrPreviewFrame');
            var spinner = document.getElementById('qrPreviewSpinner');
            var petName = document.getElementById('qrPreviewPetName');
            var petCode = document.getElementById('qrPreviewPetCode');
            var download = document.getElementById('qrPreviewDownload');

            modal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                frame.innerHTML = '';
                frame.appendChild(spinner);
                spinner.classList.remove('d-none');
                petName.textContent = button.dataset.petName;
                petCode.textContent = button.dataset.petCode ? 'ID: ' + button.dataset.petCode : '';
                download.href = button.dataset.downloadUrl;
                download.setAttribute('download', 'pet-credential-' + (button.dataset.petCode || 'pet') + '.pdf');

                var img = document.createElement('img');
                img.alt = 'QR code for ' + button.dataset.petName;
                img.style.width = '100%';
                img.onload = function () {
                    frame.innerHTML = '';
                    frame.appendChild(img);
                };
                img.src = button.dataset.previewUrl;
            });
        });
    </script>
    @endpush
@endsection