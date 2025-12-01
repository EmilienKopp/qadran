<?php

namespace App\Http\Controllers;

use App\Enums\OrganizationType;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizations = Auth::user()->organizations;

        return Inertia::render('Organization/Index', [
            'organizations' => $organizations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Organization/Create', [
            'organizationTypeOptions' => OrganizationType::toSelectOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganizationRequest $request)
    {

        $validated = $request->validated();
        Auth::user()->organizations()->create($validated);

        return redirect()->route('organization.index')
            ->with('success', 'Organization created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        return Inertia::render('Organization/Edit', [
            'organization' => $organization,
            'organizationTypeOptions' => OrganizationType::toSelectOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $validated = $request->validated();
        $organization->update($validated);

        return redirect()->route('organization.index')->with('success', 'Organization updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        try {
            $organization->delete();

            return redirect()->route('organization.index')->with('success', 'Organization deleted.');
        } catch (\Exception $e) {
            return redirect()->route('organization.index')->with('error', 'Organization cannot be deleted.');
        }
    }
}
