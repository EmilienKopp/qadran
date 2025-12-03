<?php

namespace App\Services;

class SpaceService
{
  public static function registerSpaceCookie(string $space): void
  {
    $separator = '|';
    $cookie = request()->cookie('_qadran_sp');
    if (!$cookie) {
      $spaceentry = $space . $separator . now()->toISOString();
      cookie()->queue('_qadran_sp', $spaceentry, 60 * 24 * 90); //90d
      return;
    }
    $entries = explode(',', $cookie);
    $filteredEntries = array_filter($entries, function ($entry) use ($space, $separator) {
      return !str_starts_with($entry, "{$space}{$separator}");
    });
    $newEntry = $space . $separator . now()->toISOString();
    array_unshift($filteredEntries, $newEntry);
    // Limit to last 5 spaces
    $limitedEntries = array_slice($filteredEntries, 0, 5);
    $newCookieValue = implode(',', $limitedEntries);
    cookie()->queue('_qadran_sp', $newCookieValue, 60 * 24 * 90); //90d
  }
}