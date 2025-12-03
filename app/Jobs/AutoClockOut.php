<?php

namespace App\Jobs;

use App\Models\Landlord\Tenant;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Services\PreferencesService;
use DateTimeImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AutoClockOut implements ShouldQueue
{
    use Queueable;

    protected Tenant $tenant;
    protected User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(public UserRepositoryInterface $userRepository, $tenantId, $userId)
    {
        $this->tenant = Tenant::find($tenantId);
        $this->user = $this->userRepository->find($userId);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->tenant || !$this->user) {
            return;
        }

        $this->tenant->makeCurrent();
    }

    public function autoClockOut(User $user, DateTimeImmutable $autoClockOutTime = null, DateTimeImmutable $before = null): void
    {
        $preferences = PreferencesService::for($user);
        $defaultAutoClockOutTime = $preferences->get(P_AUTO_CLOCK_OUT_TIME);
        $format = $preferences->get(P_TIME_FORMAT);
        $time = new DateTimeImmutable($autoClockOutTime ?? $defaultAutoClockOutTime);
        $user->clockEntries()
            ->when($before, fn($query) => $query->where('clocked_in_at', '<=', $before))
            ->whereNull('out')
            ->whereNotNull('in')
            ->updateRaw('"out" = DATE("in") + TIME ? AT TIME ZONE \'UTC\'', [
                $time->format($format)
            ]);
    }
}
