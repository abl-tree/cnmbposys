<?php

namespace App\Listeners;

use App\Events\StartWork;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StartWorkListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  StartWork  $event
     * @return void
     */
    public function handle(StartWork $event)
    {
        //
    }
}
