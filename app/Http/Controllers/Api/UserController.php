<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function index()
    {
        return response()->json($this->userRepository->all());
    }

    public function show(Request $request, int $id)
    {
        $user = $this->userRepository->find($id);

        return response()->json($user);
    }

    public function byWorkosId(string $workosId)
    {
        $user = $this->userRepository->findByWorkosId($workosId);

        return response()->json($user);
    }

    public function byGitHubId(string $githubId)
    {
        $user = $this->userRepository->findByGitHubId($githubId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user->load('gitHubConnection'));
    }

    public function byGoogleId(string $googleId)
    {
        $user = $this->userRepository->findByGoogleId($googleId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user->load('googleConnection'));
    }

    public function byEmail(Request $request)
    {
        $email = $request->query('email');
        $user = $this->userRepository->findByEmail($email);

        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'workos_id' => 'nullable|string',
        ]);

        $user = $this->userRepository->create($validated);

        return response()->json($user, 201);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$id,
            'workos_id' => 'nullable|string',
        ]);

        $user = $this->userRepository->update($id, $validated);
        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function destroy(int $id)
    {
        $deleted = $this->userRepository->delete($id);
        if (! $deleted) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['message' => 'User deleted'], 200);
    }
}
