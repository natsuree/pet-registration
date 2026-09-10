<?php

namespace App\Http\Controllers;

use App\Models\DewormingRecord;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DewormingController extends Controller
{
    public function index(): View
    {
        $records = DewormingRecord::query()->with('pet')->latest('administered_at')->get();

        return view('deworming', [
            'pets' => Pet::query()->orderBy('name')->get(),
            'records' => $records,
            'stats' => $this->stats($records),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        DewormingRecord::create($request->validate([
            'pet_id' => ['required', 'exists:pets,id'],
            'product' => ['required', 'string', 'max:120'],
            'administered_at' => ['required', 'date'],
            'next_due_at' => ['nullable', 'date', 'after_or_equal:administered_at'],
            'weight_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]));

        return redirect('/deworming')->with('success', 'Deworming record saved.');
    }

    private function stats(Collection $records): array
    {
        $today = today();
        $dueSoon = $today->copy()->addDays(30);
        $overdue = $records->filter(fn (DewormingRecord $record) => $record->next_due_at?->isBefore($today))->count();
        $upcoming = $records->filter(fn (DewormingRecord $record) => $record->next_due_at?->betweenIncluded($today, $dueSoon))->count();

        return ['total' => $records->count(), 'current' => $records->count() - $overdue - $upcoming, 'dueSoon' => $upcoming, 'overdue' => $overdue];
    }
}
