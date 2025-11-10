<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInterferenceRequest;
use App\Models\Interference;
use App\Repositories\InterferenceRepository;
use Illuminate\Support\Facades\Auth;

class InterferenceController extends Controller
{
    /**
     * Store a newly created interference in storage.
     */
    public function store(StoreInterferenceRequest $request)
    {
        $validated = $request->validated();

        // Create the interference
        InterferenceRepository::register([
            'user_id' => Auth::id(),
            ...$validated,
        ]);

        return redirect()->back()->with('success', 'Interference registered successfully.');
    }

    /**
     * Remove the specified interference from storage.
     */
    public function destroy(Interference $interference)
    {
        // Ensure user can only delete their own interferences
        if ($interference->user_id !== Auth::id()) {
            abort(403);
        }

        $interference->delete();

        return redirect()->back()->with('success', 'Interference deleted successfully.');
    }
}
