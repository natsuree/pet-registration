<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'user' => auth()->user(),
            'userCount' => User::where('role', User::ROLE_USER)->count(),
            'staffCount' => User::whereIn('role', [User::ROLE_STAFF, User::ROLE_ADMIN])->count(),
            'petCount' => Pet::count(),
            'recentUsers' => User::query()->latest()->take(6)->get(),
            'recentPets' => Pet::query()->latest()->take(6)->get(),
        ]);
    }

    public function users(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $role = trim((string) $request->query('role'));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('contact_number', 'like', "%{$search}%");
                });
            })
            ->when(in_array($role, [User::ROLE_USER, User::ROLE_STAFF, User::ROLE_ADMIN], true), fn($q) => $q->where('role', $role))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.users', ['users' => $users, 'search' => $search, 'roleFilter' => $role]);
    }

    public function createUser(): View
    {
        return view('admin.create-user');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'contact_number' => ['required', 'string', 'max:40'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:user,staff,admin'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function editUser(User $user): View
    {
        return view('admin.edit-user', ['editUser' => $user]);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'contact_number' => ['required', 'string', 'max:40'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,staff,admin'],
        ]);

        if (filled($validated['password'] ?? null)) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['password_confirmation']);
        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }
}
