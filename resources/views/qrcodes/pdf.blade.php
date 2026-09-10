@extends('layouts.pdf')

@section('content')
    @php
        $fields = [
            'Pet ID' => $pet->code,
            'Species' => $pet->species,
            'Breed' => $pet->breed,
            'Sex' => $pet->sex,
            'Date of Birth' => $pet->date_of_birth?->format('M d, Y'),
            'Color' => $pet->color,
            'Microchip No.' => $pet->microchip,
            'Owner' => $pet->owner_name,
            'Owner Email' => $pet->owner_email,
            'Owner Contact' => $pet->owner_number,
        ];
    @endphp

    <div class="header">
        <div class="brand">PawID &mdash; Pet Registration</div>
        <h1>Pet Credential Card</h1>
    </div>

    <div class="card">
        <div class="qr-side">
            {!! $qrSvg !!}
            <div class="qr-caption">Scan to open<br>public profile</div>
        </div>
        <div class="info-side">
            <div class="pet-name">{{ $pet->name }}</div>
            <table class="info-table">
                @foreach ($fields as $label => $value)
                    <tr>
                        <td class="label">{{ $label }}</td>
                        <td class="value">{{ $value ?: '&mdash;' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <p class="footer">Generated {{ now()->format('M d, Y') }} &middot; Keep this card with your pet's records.</p>
@endsection
