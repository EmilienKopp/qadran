<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TokenController extends Controller
{
    /**
     * Generate a personal access token for API authentication
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'token_name' => 'required|string',
            'abilities' => 'array',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Create token with specified abilities or default abilities
        $abilities = $request->abilities ?? ['*'];
        $token = $user->createToken($request->token_name, $abilities);

        return response()->json([
            'token' => $token->plainTextToken,
            'user_id' => $user->id,
            'abilities' => $abilities,
        ], 201);
    }

    /**
     * List all tokens for the authenticated user
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'tokens' => $user->tokens->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'abilities' => $token->abilities,
                    'created_at' => $token->created_at,
                    'last_used_at' => $token->last_used_at,
                ];
            })
        ]);
    }

    /**
     * Revoke a specific token
     */
    public function revoke(Request $request, $tokenId)
    {
        $user = $request->user();
        $token = $user->tokens()->find($tokenId);

        if (!$token) {
            return response()->json([
                'message' => 'Token not found'
            ], 404);
        }

        $token->delete();

        return response()->json([
            'message' => 'Token revoked successfully'
        ]);
    }

    /**
     * Revoke all tokens for the authenticated user
     */
    public function revokeAll(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'All tokens revoked successfully'
        ]);
    }
}
