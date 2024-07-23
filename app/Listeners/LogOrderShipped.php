<?php

namespace App\Listeners;
use Illuminate\Support\Facades\Log;
use App\Events\OrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOrderShipped implements ShouldQueue
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
    public function handle(OrderShipped $event): void
    {
        //
        Log::debug(__CLASS__,[$event->ahihi]);
    }
}
