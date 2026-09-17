<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function dashboard(): View
    {
        return view('staff.dashboard', [
            'user' => auth()->user(),
            'userCount' => User::where('role', User::ROLE_USER)->count(),
            'staffCount' => User::whereIn('role', [User::ROLE_STAFF, User::ROLE_ADMIN])->count(),
            'petCount' => Pet::count(),
            'recentPets' => Pet::query()->latest()->take(6)->get(),
            'recentUsers' => User::where('role', User::ROLE_USER)->latest()->take(6)->get(),
        ]);
    }

    public function users(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $users = User::query()
            ->where('role', User::ROLE_USER)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('contact_number', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('staff.users', ['users' => $users, 'search' => $search]);
    }

    public function userDetails(User $user): View
    {
        $pets = Pet::query()
            ->where('owner_email', $user->email)
            ->orderBy('name')
            ->with(['vaccinations', 'dewormingRecords'])
            ->get();

        return view('staff.user-details', ['client' => $user, 'pets' => $pets]);
    }

    public function allPets(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $species = trim((string) $request->query('species'));

        $speciesList = Pet::query()->distinct()->orderBy('species')->pluck('species')->filter();

        $pets = Pet::query()
            ->with('vaccinations')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('owner_name', 'like', "%{$search}%")
                        ->orWhere('owner_email', 'like', "%{$search}%");
                });
            })
            ->when($species !== '', fn($q) => $q->where('species', $species))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('staff.pets', [
            'pets' => $pets,
            'search' => $search,
            'speciesFilter' => $species,
            'speciesList' => $speciesList,
        ]);
    }

    public function createPet(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $clients = User::query()
            ->where('role', User::ROLE_USER)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->take(20)
            ->get();

        return view('staff.register-pet', [
            'clients' => $clients,
            'search' => $search,
            'selectedClient' => $request->integer('client') ? User::find($request->integer('client')) : null,
        ]);
    }

    public function storePet(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'owner_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:120'],
            'species' => ['required', 'string', 'max:60'],
            'breed' => ['nullable', 'string', 'max:120'],
            'sex' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'color' => ['nullable', 'string', 'max:80'],
            'microchip' => ['nullable', 'string', 'max:60', 'unique:pets,microchip'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $owner = User::query()
            ->whereKey($validated['owner_id'])
            ->where('role', User::ROLE_USER)
            ->firstOrFail();

        $pet = Pet::create([
            'name' => $validated['name'],
            'species' => $validated['species'],
            'breed' => $validated['breed'] ?? null,
            'sex' => $validated['sex'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'color' => $validated['color'] ?? null,
            'microchip' => $validated['microchip'] ?? null,
            'owner_name' => $owner->name,
            'owner_email' => $owner->email,
            'owner_number' => $owner->contact_number ?? '',
        ]);

        if ($request->hasFile('photo')) {
            $pet->update([
                'photo_path' => $request->file('photo')->store('pet-photos', 'public'),
            ]);
        }

        return redirect()
            ->route('staff.users.show', $owner)
            ->with('success', "{$pet->name} was registered for {$owner->name}.");
    }
}
