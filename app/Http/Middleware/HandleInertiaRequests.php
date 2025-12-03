<?php

namespace App\Http\Middleware;

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
        $userId = $request->user()?->id;
        $user = $userId ? app(UserRepositoryInterface::class)->find($userId, ['roles', 'organizations']) : null;
        
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
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
            ],
            'features' => [
                'voiceAssistant' => Tenant::current()?->features()->value(VoiceAssistant::class) ?? false,
                'voiceAssistantMode' => $request->user()
                    ? Feature::value(VoiceAssistantMode::class, $request->user())
                    : false,
            ],
        ];
    }
}
