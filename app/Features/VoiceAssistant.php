<?php

namespace App\Features;

use App\Enums\VoiceAssistanPlan;
use Illuminate\Support\Lottery;

class VoiceAssistant
{
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(mixed $scope): mixed
    {
        $specialTenants = array_keys(config('plans.associations'));
        if (in_array($scope->tenant->subdomain, $specialTenants)) {
            return VoiceAssistanPlan::SECRET;
        }

        return VoiceAssistanPlan::DISABLED;
    }
}
