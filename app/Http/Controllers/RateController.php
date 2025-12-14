<?php

namespace App\Http\Controllers;

use App\Enums\RateFrequency;
use App\Enums\RateType;
use App\Enums\RateTypeScope;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(?string $account = null)
    {
        $rates = Rate::with(['organization', 'project', 'user'])
            ->active()
            ->get();
            
        return Inertia::render('Rate/Index', [
            'rates' => $rates,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(?string $account = null)
    {
        return Inertia::render('Rate/Create', [
            'frequenciesOptions' => RateFrequency::toSelectOptions(),
            'scopesOptions' => RateTypeScope::toSelectOptions(),
            'organizations' => Auth::user()->organizations,
            'projects' => Auth::user()->projects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ?string $account = null)
    {
        $validated = $request->validate([
            'rate_type' => ['required', new Enum(RateType::class)],
            'rate_frequency' => ['required', new Enum(RateFrequency::class)],
            'organization_id' => 'nullable|exists:tenant.organizations,id',
            'project_id' => 'nullable|exists:tenant.projects,id',
            'user_id' => 'nullable|exists:tenant.users,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'overtime_multiplier' => 'nullable|numeric|min:1',
            'holiday_multiplier' => 'nullable|numeric|min:1',
            'special_multiplier' => 'nullable|numeric|min:1',
            'custom_multiplier_rate' => 'nullable|numeric|min:1',
            'custom_multiplier_label' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
        ]);

        Rate::create($validated);

        return redirect()
            ->route('rate.index')
            ->with('success', 'Rate created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(?string $account, Rate $rate)
    {
        $rate->load(['organization', 'project', 'user']);

        return Inertia::render('Rate/Show', [
            'rate' => $rate,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(?string $account, Rate $rate)
    {
        $rate->load(['organization', 'project', 'user']);

        return Inertia::render('Rate/Edit', [
            'rate' => $rate,
            'frequenciesOptions' => RateFrequency::toSelectOptions(),
            'scopesOptions' => RateTypeScope::toSelectOptions(),
            'organizations' => Auth::user()->organizations,
            'projects' => Auth::user()->projects,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ?string $account, Rate $rate)
    {
        $validated = $request->validate([
            'rate_type' => ['required', new Enum(RateType::class)],
            'rate_frequency' => ['required', new Enum(RateFrequency::class)],
            'organization_id' => 'nullable|exists:tenant.organizations,id',
            'project_id' => 'nullable|exists:tenant.projects,id',
            'user_id' => 'nullable|exists:tenant.users,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'overtime_multiplier' => 'nullable|numeric|min:1',
            'holiday_multiplier' => 'nullable|numeric|min:1',
            'special_multiplier' => 'nullable|numeric|min:1',
            'custom_multiplier_rate' => 'nullable|numeric|min:1',
            'custom_multiplier_label' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
        ]);

        $rate->update($validated);

        return redirect()
            ->route('rate.index')
            ->with('success', 'Rate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(?string $account, Rate $rate)
    {
        try {
            $rate->delete();

            return redirect()
                ->route('rate.index')
                ->with('success', 'Rate deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('rate.index')
                ->with('error', 'Unable to delete rate.');
        }
    }
}
