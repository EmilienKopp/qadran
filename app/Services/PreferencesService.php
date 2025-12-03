<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use DateTimeImmutable;
use Illuminate\Support\Arr;

class PreferencesService
{

  private UserRepositoryInterface $userRepository;
  private array $allowedKeys;
  private User $user;
  private static ?PreferencesService $instance = null;

  private function __construct(UserRepositoryInterface $userRepository)
  {
    $this->userRepository = $userRepository;
    $this->allowedKeys = [];
    $preferencesConfigObject = config('preferences');
    // extract allowed preference keys and nested paths from config
    foreach ($preferencesConfigObject as $key => $value) {
      if (is_array($value)) {
        $this->allowedKeys[] = $key;
        foreach (array_keys($value) as $subKey) {
          $this->allowedKeys[] = "{$key}.{$subKey}";
        }
      } else {
        $this->allowedKeys[] = $key;
      }
    }
  }

  public static function for(User $user): PreferencesService
  {
    self::$instance ??= new self(app(UserRepositoryInterface::class));
    self::$instance->user = $user;
    return self::$instance;
  }

  public function setAutoClockOutTime(User $user, ?DateTimeImmutable $autoClockOutTime): void
  {
    $this->userRepository->update($user->id, [
      'preferences->auto_clock_out_time' => $autoClockOutTime ? $autoClockOutTime->format('H:i:s') : null,
    ]);

    // Apply auto clock-out to existing clock entries
    $this->userRepository->autoClockOut($user, $autoClockOutTime);
  }

  public function get(string $path)
  {
    $preferences = config('preferences');
    if ($this->user?->preferences) {
      $preferences = array_merge($preferences, $this->user->preferences);
    }
    return Arr::get($preferences, $path);
  }
}