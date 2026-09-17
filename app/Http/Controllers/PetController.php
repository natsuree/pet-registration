<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PetController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $pets = Pet::query()
            ->when(! $user->canManageRecords(), fn ($q) => $q->where('owner_email', $user->email))
            ->orderBy('name')
            ->get();

        return view('mypets', ['pets' => $pets]);
    }

    public function show(Pet $pet): View
    {
        $this->authorizePet($pet);
        $pet->load(['vaccinations', 'dewormingRecords']);
        return view('pet-details', ['pet' => $pet]);
    }

    private function authorizePet(Pet $pet): void
    {
        $user = Auth::user();
        abort_unless($user->canManageRecords() || $pet->owner_email === $user->email, 403, 'You do not have access to this pet record.');
    }
}
