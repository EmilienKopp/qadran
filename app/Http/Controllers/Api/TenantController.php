<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Landlord\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        return response()->json(Tenant::all());
    }

    public function show(int $id)
    {
        $tenant = Tenant::find($id);

        return response()->json($tenant);
    }

    public function byDomain(Request $request)
    {
        $domain = $request->query('domain');
        $tenant = Tenant::where('domain', $domain)->first();

        return response()->json($tenant);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:tenants,domain',
        ]);

        $tenant = Tenant::create($validated);

        return response()->json($tenant, 201);
    }

    public function update(Request $request, int $id)
    {
        $tenant = Tenant::find($id);
        if (! $tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'domain' => 'sometimes|string|unique:tenants,domain,'.$id,
        ]);

        $tenant->update($validated);

        return response()->json($tenant);
    }

    public function destroy(int $id)
    {
        $tenant = Tenant::find($id);
        if (! $tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        $tenant->delete();

        return response()->json(['message' => 'Tenant deleted'], 200);
    }
}
