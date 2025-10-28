<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use WorkOS\UserManagement;
use App\Models\Landlord\Tenant;
use WorkOS\WorkOS;

class AuthenticatedSessionController extends Controller
{
    public function __construct(public UserManagement $userManager)
    {}

    /**
     * Display the login view.
     */
    public function create()
    {
        $authURL = $this->userManager->getAuthorizationUrl(
            redirectUri: route('authenticate'),
            organizationId: Tenant::current()->org_id,
            provider: 'authkit'
        );
        // return Inertia::render('Auth/Login', [
        //     'canResetPassword' => Route::has('password.request'),
        //     'status' => session('status'),
        // ]);
        return redirect($authURL);
    }

    public function authenticate(Request $request)
    {
        $code = $request->get('code');
        if (!$code) {
            return redirect()->route('login')->withErrors(['msg' => 'Authorization code not provided.']);
        }

        $response = $this->userManager->authenticateWithCode(
             code: $code,
             clientId: config('workos.client_id'),
        );

        $appUser = \App\Models\User::where('workos_id', $response->user->id)->first();
        if (!$appUser) {
            //Create user if not exists
            $appUser = \App\Models\User::create([
                'first_name' => $response->user->firstName,
                'last_name' => $response->user->lastName,
                'email' => $response->user->email,
                'workos_id' => $response->user->id,
                'password' => bcrypt(str()->random(16)),
                'email_verified_at' => now(), // Users authenticated via WorkOS are considered verified
            ]);
            $appUser->assignRole(['user']);
        }

        Auth::guard('tenant')->login($appUser);

        return to_route('dashboard');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        \Log::info("store");
        $request->authenticate();
        \Log::info("auth");
        $request->session()->regenerate();
        \Log::info("AWDASDASD");
        return redirect()->intended(route('dashboard', ['account' => 'account'],absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
