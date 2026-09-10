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
        $pets = Pet::query()
            ->where('owner_email', Auth::user()->email)
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

    public function destroy(Pet $pet): RedirectResponse
    {
        $this->authorizePet($pet);

        $pet->delete();

        return redirect()->route('pets.index')->with('success', "{$pet->name} was deleted.");
    }

    public function create(): View
    {
        return view('register');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $pet = Pet::create(array_merge($request->validate([
            'name' => ['required', 'string', 'max:120'],
            'species' => ['required', 'string', 'max:60'],
            'breed' => ['nullable', 'string', 'max:120'],
            'sex' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'color' => ['nullable', 'string', 'max:80'],
            'microchip' => ['nullable', 'string', 'max:60', 'unique:pets,microchip'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]), [
            'owner_name' => $user->name,
            'owner_email' => $user->email,
            'owner_number' => $user->contact_number ?? '',
        ]));

        if ($request->hasFile('photo')) {
            $pet->update([
                'photo_path' => $request->file('photo')->store('pet-photos', 'public'),
            ]);
        }

        return redirect()->route('pets.index')->with('success', "{$pet->name} was registered successfully.");
    }

    private function authorizePet(Pet $pet): void
    {
        abort_unless($pet->owner_email === Auth::user()->email, 403, 'You do not have access to this pet record.');
    }
}
