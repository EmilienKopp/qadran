<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Context;
use Native\Laravel\Events\App\ApplicationBooted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
