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
        $user = auth()->user();
        $pets = Pet::query()
            ->when(! $user->canManageRecords(), fn ($q) => $q->where('owner_email', $user->email))
            ->orderBy('name')
            ->get();

        $records = DewormingRecord::query()
            ->with('pet')
            ->whereIn('pet_id', $pets->pluck('id'))
            ->latest('administered_at')
            ->get();

        return view('deworming', [
            'pets' => $pets,
            'records' => $records,
            'stats' => $this->stats($records),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->canManageRecords(), 403, 'Only OCV staff can add deworming records.');

        $validated = $request->validate([
            'pet_id' => ['required', 'exists:pets,id'],
            'product' => ['required', 'string', 'max:120'],
            'administered_at' => ['required', 'date', 'before_or_equal:today'],
            'next_due_at' => ['nullable', 'date', 'after_or_equal:administered_at'],
            'weight_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DewormingRecord::create($validated);
        return redirect()->back()->with('success', 'Deworming record saved.');
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
