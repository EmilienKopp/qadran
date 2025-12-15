<?php

namespace App\Http\Middleware;

use App\DTOs\WorkOSOrganizationMembership;
use App\Features\VoiceAssistant;
use App\Features\VoiceAssistantMode;
use App\Models\Landlord\Tenant;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Inertia\Middleware;
use Laravel\Pennant\Feature;

class HandleInertiaRequests extends Middleware
{
    public function __construct(private UserRepositoryInterface $userRepository, private \App\Services\WorkOSService $workosService)
    {
    }

    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Only load user data if we have an active tenant context
        // This prevents database errors during OAuth callbacks on the root domain
        $tenant = Tenant::current();
        $user = null;

        if ($tenant) {
            $userId = $request->user()?->id;
            $user = $userId ? $this->userRepository->find($userId, ['roles', 'organizations']) : null;
        }

        $memberships = [];
        if($user?->workos_id) {
            $memberships = $this->workosService->getUserOrganizationMemberships($user->workos_id);
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'memberships' => $memberships,
            ],
            'timezone' => $user?->timezone ?? $user?->preferences['timezone'] ?? null,
            'context' => [
                'tenant' => Context::get('tenantId'),
                'domain' => Context::get('domain'),
                'executionContext' => Context::get('executionContext'),
                'host' => Context::get('host'),
                'availableTenants' => Context::get('availableTenants'),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'info' => fn () => $request->session()->get('info'),
                'data' => fn () => $request->session()->get('data'),
            ],
            'enums' => [
                'roles' => \App\Enums\RoleEnum::toSelectOptions(),
                'report_types' => \App\Enums\ReportTypes::toSelectOptions(),
                'rate_types' => \App\Enums\RateType::toSelectOptions(),
            ],
            'features' => [
                'voiceAssistant' => $tenant?->features()->value(VoiceAssistant::class) ?? false,
                'voiceAssistantMode' => $tenant && $request->user()
                    ? Feature::for($request->user())->value(VoiceAssistantMode::class)
                    : false,
            ],
        ];
    }
}
