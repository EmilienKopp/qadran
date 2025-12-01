<?php

namespace App\Utils;

use Illuminate\Validation\ValidationException;

class InertiaHelper
{
    public static function fail(string $message, array $errors = [])
    {
        throw ValidationException::withMessages([
            'error' => [$message],
            ...$errors,
        ]);
    }
}
