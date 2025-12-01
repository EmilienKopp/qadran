<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ClockEntryRepositoryInterface;
use Illuminate\Http\Request;

class ClockEntryController extends Controller
{
    public function __construct(
        protected ClockEntryRepositoryInterface $clockEntryRepository
    ) {}

    public function index()
    {
        return response()->json($this->clockEntryRepository->all());
    }

    public function show(int $id)
    {
        $entry = $this->clockEntryRepository->find($id);

        return response()->json($entry);
    }

    public function byUser(int $userId)
    {
        $entries = $this->clockEntryRepository->findByUser($userId);

        return response()->json($entries);
    }

    public function activeByUser(int $userId)
    {
        $entry = $this->clockEntryRepository->findActiveByUser($userId);

        return response()->json($entry);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date',
        ]);

        $entry = $this->clockEntryRepository->create($validated);

        return response()->json($entry, 201);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'start_time' => 'sometimes|date',
            'end_time' => 'nullable|date',
        ]);

        $entry = $this->clockEntryRepository->update($id, $validated);
        if (! $entry) {
            return response()->json(['error' => 'Clock entry not found'], 404);
        }

        return response()->json($entry);
    }

    public function destroy(int $id)
    {
        $deleted = $this->clockEntryRepository->delete($id);
        if (! $deleted) {
            return response()->json(['error' => 'Clock entry not found'], 404);
        }

        return response()->json(['message' => 'Clock entry deleted'], 200);
    }
}
