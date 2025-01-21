<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait ExtendEnums
{
  /**
   * Get the enum values.
   *
   * @return array
   */
  public static function values(): array
  {
    return array_column(self::cases(), 'value');
  }

  public static function collect(): \Illuminate\Support\Collection
  {
    return collect(array_column(self::cases(), 'value'));
  }

  public static function toSelectOptions(): \Illuminate\Support\Collection
  {
    return self::collect()->map( function ($obj) {
      $readable = Str::title(Str::replace('_', ' ', $obj));
      return [
        'value' => $obj,
        'label' => $readable,
        'name' => $readable,
      ];
    });
  }

  /**
   * Get the enum keys.
   *
   * @return array
   */
  public static function keys(): array
  {
    return array_keys(self::cases());
  }

  /**
   * Get the enum key-value pairs. (Alias of `cases()`)
   *
   * @return array
   */
  public static function entries(): array
  {
    return self::cases();
  }
  
  public static function random(): string
  {
    return Arr::random(self::values());
  }
}
