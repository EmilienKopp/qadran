<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClockEntryRequest;
use App\Http\Requests\UpdateClockEntryRequest;
use App\Models\ClockEntry;
use App\Repositories\ClockEntryRepository;
use App\Utils\InertiaHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ClockEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClockEntryRequest $request)
    {
        $validated = $request->validated();

        // If this is a clock out (has 'out' field), handle it separately
        if (isset($validated['out'])) {
            ClockEntryRepository::clockOut(Auth::id(), $validated['out']);
        } else {
            // Clock in
            ClockEntryRepository::clockIn([
                'user_id' => Auth::id(),
                ...$validated,
            ]);
        }

        return to_route('dashboard');
    }

    public function in(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'project_id' => ['required', 'exists:projects,id'],
            'in' => ['nullable', 'date'],
            'timezone' => ['nullable', 'string'],
        ]);

        ClockEntryRepository::clockIn($validated);

        return to_route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClockEntry $clockEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClockEntry $clockEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClockEntryRequest $request, ClockEntry $clockEntry)
    {
        $validated = $request->validated();
        $clockEntry->update($validated);
        
        return back()->with('success', 'Clock entry updated successfully.');
    }

    /**
     * Batch update clock entries
     */
    public function batchUpdate(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'entries' => 'required|array',
            'entries.*.id' => 'required|exists:clock_entries,id',
            'entries.*.in_time' => 'required|date',
            'entries.*.out_time' => 'nullable|date',
        ]);

        foreach ($validated['entries'] as $entryData) {
            $entry = ClockEntry::find($entryData['id']);
            if ($entry && $entry->user_id === auth()->user()->id) {
                $entry->update([
                    'in' => $entryData['in_time'],
                    'out' => $entryData['out_time'] ?? null,
                ]);
            }
        }

        return back()->with('success', 'Clock entries updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClockEntry $clockEntry)
    {
        try {
            $success = ClockEntryRepository::delete($clockEntry);
            if (! $success) {
                InertiaHelper::fail('Could not delete the clock entry.');
            }
        } catch (\Exception $e) {
            InertiaHelper::fail('Could not delete the clock entry.');
        }

        return redirect()->back();
    }
}
