<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Landlord\Tenant;
use App\Repositories\UserRepositoryInterface;
use App\Services\SpaceService;
use App\Support\TenantUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// use Native\Desktop\Facades\Settings;
// use WorkOS\UserManagement;

class AuthenticatedSessionController extends Controller
{
    // WorkOS authentication commented out - using GitHub OAuth as primary
    // public function __construct(public UserManagement $userManager, public UserRepositoryInterface $userRepository) {}

    public function __construct(public UserRepositoryInterface $userRepository)
    {
    }

    /**
     * Display the login view.
     * WorkOS authentication commented out - now using GitHub OAuth
     */
    // public function create()
    // {
    //     $tenant = Tenant::current();

    //     if (! $tenant) {
    //         return redirect()->route('tenant.welcome')
    //             ->withErrors(['msg' => 'No tenant found. Please select your space first.']);
    //     }
    //     // Generate redirect URI using the current request's base URL
    //     // This ensures it works in both web (localhost:8000) and NativePHP (127.0.0.1:8100)
    //     $redirectUri = url('/authenticate');

    //     $authURL = $this->userManager->getAuthorizationUrl(
    //         redirectUri: $redirectUri,
    //         organizationId: $tenant->org_id,
    //         provider: 'authkit'
    //     );

    //     return Inertia::location($authURL);
    // }

    // WorkOS authentication callback - commented out
    // public function authenticate(Request $request): RedirectResponse
    // {
    //     $code = $request->get('code');
    //     if (! $code) {
    //         return redirect()->route('login')->withErrors(['msg' => 'Authorization code not provided.']);
    //     }

    //     $account = $request->route('account');

    //     $response = $this->userManager->authenticateWithCode(
    //         code: $code,
    //         clientId: config('workos.client_id'),
    //     );

    //     $appUser = $this->userRepository->findByWorkosId($response->user->id);

    //     \Log::debug('AuthenticatedSessionController authenticate', [
    //         'workos_user_id' => $response->user->id,
    //         'appUser' => $appUser,
    //         'is_desktop' => \App\Support\RequestContextResolver::isDesktop(),
    //         'repository_class' => get_class(app(UserRepositoryInterface::class)),
    //     ]);

    //     if (! $appUser) {
    //         \Log::debug('Creating new user for WorkOS ID', [
    //             'workos_id' => $response->user->id,
    //             'email' => $response->user->email,
    //         ]);
    //         // In desktop mode, user creation should happen on the web API
    //         // For now, we'll fetch user data through the API or fail gracefully
    //         if (\App\Support\RequestContextResolver::isDesktop()) {
    //             \Log::debug('User not found in desktop mode, redirecting to login');

    //             return redirect()->route('login')
    //                 ->withErrors(['msg' => 'User not found. Please sign in through the web application first.']);
    //         }

    //         // Create user if not exists (web mode only)
    //         $appUser = \App\Models\User::create([
    //             'first_name' => $response->user->firstName,
    //             'last_name' => $response->user->lastName,
    //             'email' => $response->user->email,
    //             'workos_id' => $response->user->id,
    //             'password' => bcrypt(str()->random(16)),
    //             'email_verified_at' => now(), // Users authenticated via WorkOS are considered verified
    //         ]);
    //         $appUser->assignRole(['user']);
    //     }

    //     if (\App\Support\RequestContextResolver::isDesktop()) {
    //         // Set user in settings
    //         Settings::set('authenticated_user', $appUser->toArray());
    //     }

    //     Auth::guard('tenant')->login($appUser);

    //     \Log::debug('User authenticated', [
    //         'user_id' => $appUser->id,
    //         'session_id' => $request->session()->getId(),
    //         'is_desktop' => \App\Support\RequestContextResolver::isDesktop(),
    //         'account' => $account,
    //     ]);

    //     return to_route('dashboard');
    // }

    public function create()
    {
        $cookie = request()->cookie('_qadran_sp');
        $rememberedSpaces = explode(',', $cookie ?? null);
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'rememberedSpaces' => empty($cookie) ? [] : $rememberedSpaces,
        ]);
    }

    /**
     * Handle an incoming authentication request (password-based fallback).
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Find tenant by space identifier
        $tenant = Tenant::where('host', $request->input('space'))->first();
        if (!$tenant) {
            return back()->withErrors([
                'space' => 'Space not found. Please check your organization identifier.',
            ])->onlyInput('email', 'space');
        }

        // Set tenant context before authentication
        $tenant->makeCurrent();

        $request->authenticate();
        $request->session()->regenerate();

        // Remember the space in a cookie for future visits
        SpaceService::registerSpaceCookie($tenant->host);

        return redirect()->intended(TenantUrl::route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     * Simplified - no WorkOS dual-session complexity!
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
