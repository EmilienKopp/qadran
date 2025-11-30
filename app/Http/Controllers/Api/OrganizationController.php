<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\OrganizationRepositoryInterface;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct(
        protected OrganizationRepositoryInterface $organizationRepository
    ) {}

    public function index()
    {
        return response()->json($this->organizationRepository->all());
    }

    public function show(int $id)
    {
        $organization = $this->organizationRepository->find($id);

        return response()->json($organization);
    }

    public function byUser(int $userId)
    {
        $organizations = $this->organizationRepository->findByUser($userId);

        return response()->json($organizations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $organization = $this->organizationRepository->create($validated);

        return response()->json($organization, 201);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        $organization = $this->organizationRepository->update($id, $validated);
        if (! $organization) {
            return response()->json(['error' => 'Organization not found'], 404);
        }

        return response()->json($organization);
    }

    public function destroy(int $id)
    {
        $deleted = $this->organizationRepository->delete($id);
        if (! $deleted) {
            return response()->json(['error' => 'Organization not found'], 404);
        }

        return response()->json(['message' => 'Organization deleted'], 200);
    }
}
