<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Linkxtr\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $pets = Pet::query()
            ->when(! $user->canManageRecords(), fn ($q) => $q->where('owner_email', $user->email))
            ->orderBy('name')
            ->get();
        return view('qrcodes', ['pets' => $pets]);
    }

    public function show(Pet $pet): \Symfony\Component\HttpFoundation\Response
    {
        $this->authorizeOwner($pet);
        $qrSvg = QrCode::format('svg')
            ->size(180)
            ->margin(1)
            ->errorCorrection('M')
            ->generate(url('/pets/'.$pet->id));

        $pdf = Pdf::loadView('qrcodes.pdf', ['pet' => $pet, 'qrSvg' => $qrSvg]);

        return $pdf->download('pet-credential-'.($pet->code ?? $pet->id).'.pdf');
    }

    public function preview(Pet $pet): \Symfony\Component\HttpFoundation\Response
    {
        $this->authorizeOwner($pet);

        return $this->qrResponse($pet, inline: true);
    }

    private function authorizeOwner(Pet $pet): void
    {
        $user = auth()->user();
        abort_unless($user->canManageRecords() || $pet->owner_email === $user->email, 403, 'You do not have access to this pet record.');
    }

    private function qrResponse(Pet $pet, bool $inline): \Symfony\Component\HttpFoundation\Response
    {
        $svg = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('M')
            ->generate(url('/pets/'.$pet->id));

        $response = response($svg)->header('Content-Type', 'image/svg+xml');

        if (! $inline) {
            $response->header('Content-Disposition', 'attachment; filename="qr-'.$pet->code.'.svg"');
        }

        return $response;
    }
}
