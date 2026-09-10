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
        $records = Vaccination::query()->with('pet')->latest('administered_at')->get();

        return view('vaccinations', [
            'pets' => Pet::query()->orderBy('name')->get(),
            'records' => $records,
            'stats' => $this->stats($records),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Vaccination::create($request->validate([
            'pet_id' => ['required', 'exists:pets,id'],
            'vaccine' => ['required', 'string', 'max:120'],
            'administered_at' => ['required', 'date'],
            'next_due_at' => ['nullable', 'date', 'after_or_equal:administered_at'],
            'veterinarian' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]));

        return redirect('/vaccinations')->with('success', 'Vaccination record saved.');
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
