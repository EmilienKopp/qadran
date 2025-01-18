<?php

namespace App\Http\Controllers;

use App\Models\ClockEntry;
use App\Http\Requests\StoreClockEntryRequest;
use App\Http\Requests\UpdateClockEntryRequest;
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

        $entry = ClockEntry::where('user_id', Auth::id())
            ->where('project_id', $validated['project_id'])
            ->whereNull('out')
            ->orderBy('in', 'desc')
            ->first();
        if(!$entry) {
            $entry = ClockEntry::create([
                ...$validated,
                'in' => now(),
            ]);
        } else {
            $entry->update([
                ...$validated,
                'out' => now(),
            ]);
        }

        return to_route('dashboard');
    }

    public function in(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'in' => ['required', 'date']
        ]);
        
        ClockEntry::create($validated);

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClockEntry $clockEntry)
    {
        //
    }
}
