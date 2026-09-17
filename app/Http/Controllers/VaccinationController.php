<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Vaccination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class VaccinationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $pets = Pet::query()
            ->when(! $user->canManageRecords(), fn ($q) => $q->where('owner_email', $user->email))
            ->orderBy('name')
            ->get();

        $records = Vaccination::query()
            ->with('pet')
            ->whereIn('pet_id', $pets->pluck('id'))
            ->latest('administered_at')
            ->get();

        return view('vaccinations', [
            'pets' => $pets,
            'records' => $records,
            'stats' => $this->stats($records),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->canManageRecords(), 403, 'Only OCV staff can add vaccination records.');

        $validated = $request->validate([
            'pet_id' => ['required', 'exists:pets,id'],
            'vaccine' => ['required', 'string', 'max:120'],
            'administered_at' => ['required', 'date', 'before_or_equal:today'],
            'next_due_at' => ['nullable', 'date', 'after_or_equal:administered_at'],
            'veterinarian' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Vaccination::create($validated);
        return redirect()->back()->with('success', 'Vaccination record saved.');
    }

    private function stats(Collection $records): array
    {
        $today = today();
        $dueSoon = $today->copy()->addDays(30);
        $overdue = $records->filter(fn (Vaccination $record) => $record->next_due_at?->isBefore($today))->count();
        $upcoming = $records->filter(fn (Vaccination $record) => $record->next_due_at?->betweenIncluded($today, $dueSoon))->count();

        return ['total' => $records->count(), 'current' => $records->count() - $overdue - $upcoming, 'dueSoon' => $upcoming, 'overdue' => $overdue];
    }
}
