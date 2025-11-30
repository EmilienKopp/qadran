<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Context;
use Native\Laravel\Events\App\ApplicationBooted;

class ApplicationBootedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ApplicationBooted $event): void
    {
        Context::set('destkop', true);
    }
}
