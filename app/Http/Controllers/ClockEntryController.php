<?php

namespace App\Http\Controllers;

use App\Models\ClockEntry;
use App\Repositories\ClockEntryRepository;
use App\Http\Requests\StoreClockEntryRequest;
use App\Http\Requests\UpdateClockEntryRequest;
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

        $account = $request->route('account');
        
        if ($account) {
            return to_route('dashboard', ['account' => $account]);
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

        $account = $request->route('account');
        
        if ($account) {
            return to_route('dashboard', ['account' => $account]);
        }
        
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
        try {
            $success = ClockEntryRepository::delete($clockEntry);
            if(!$success) {
                InertiaHelper::fail('Could not delete the clock entry.');
            }
        } catch (\Exception $e) {
            InertiaHelper::fail('Could not delete the clock entry.');
        }

        return redirect()->back();
    }
}
