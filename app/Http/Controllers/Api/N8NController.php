<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;
use App\Models\Landlord\Tenant;

class N8NController extends Controller
{

    public function decryptTenant(Request $request)
    {
        $request->validate(['tenant_id' => 'required']);

        if ($request->header('X-N8N-Secret') !== config('services.n8n.secret')) {
            abort(403);
        }

        try {
            $tenant = Tenant::where('hash', $request->input('tenant_id'))->firstOrFail();
            $tenant->makeCurrent();
            $tenantId = $tenant->id;

            // Token value here is the HASHED token from personal_access_tokens.token column
            // It is just used as an extra layer to hide user credentials
            $tokenValue = $request->input('token');
            $userId = PersonalAccessToken::where('token', $tokenValue)->first()?->tokenable_id;

            return response()->json(['tenant_id' => $tenantId, 'user_id' => $userId]);
        } catch (\Exception $e) {
            abort(401, 'Invalid credentials');
        }
    }


    /**
     * Handle incoming webhook requests from n8n.
     *
     * This controller processes requests sent by n8n workflows,
     * allowing for integration and automation within the application.
     */
    public function handleWebhook()
    {
        //TODO
    }
}