<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\DataAccess\Facades\User as UserDataAccess;
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
        $tenant = Tenant::current();

        if (!$tenant) {
            return redirect()->route('tenant.welcome')
                ->withErrors(['msg' => 'No tenant found. Please select your space first.']);
        }
        // Generate redirect URI using the current request's base URL
        // This ensures it works in both web (localhost:8000) and NativePHP (127.0.0.1:8100)
        $redirectUri = url('/authenticate');

        $authURL = $this->userManager->getAuthorizationUrl(
            redirectUri: $redirectUri,
            organizationId: $tenant->org_id,
            provider: 'authkit'
        );
        // return Inertia::render('Auth/Login', [
        //     'canResetPassword' => Route::has('password.request'),
        //     'status' => session('status'),
        // ]);

        // Use Inertia::location() for external OAuth redirects to avoid CORS issues
        // This forces a full page redirect instead of XHR
        return Inertia::location($authURL);
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

        // Use DataAccess abstraction to support both web and desktop modes
        $appUser = UserDataAccess::firstWhere('workos_id', $response->user->id);

        if (!$appUser) {
            // In desktop mode, user creation should happen on the web API
            // For now, we'll fetch user data through the API or fail gracefully
            if (\App\Support\RequestContextResolver::isDesktop()) {
                return redirect()->route('login')
                    ->withErrors(['msg' => 'User not found. Please sign in through the web application first.']);
            }

            //Create user if not exists (web mode only)
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
