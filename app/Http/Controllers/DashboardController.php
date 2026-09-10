<?php

namespace App\Http\Controllers;

use App\Models\DewormingRecord;
use App\Models\Pet;
use App\Models\Vaccination;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $pets = Pet::query()
            ->where('owner_email', $user->email)
            ->orderBy('name')
            ->get();

        $petEmails = $pets->pluck('id');

        return view('dashboard', [
            'user' => $user,
            'pets' => $pets,
            'petCount' => $pets->count(),
            'vaccinationCount' => Vaccination::whereIn('pet_id', $petEmails)->count(),
            'dewormingCount' => DewormingRecord::whereIn('pet_id', $petEmails)->count(),
        ]);
    }
}
