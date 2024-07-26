<?php

namespace App\Listeners;
use Illuminate\Support\Facades\Log;

use App\Events\OrderCreated;
use Illuminate\Support\Facades\DB;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOrder implements ShouldQueue
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
    public function handle(OrderCreated $event): void
    {
        //
        Log::debug(__CLASS__,[$event->order]);
        $order = $event->order;
        DB::table('order_logs')->insert([
            'order_id' => $order->id,
            'created_at' => now(),
            'updated_at' => now(),
            'status' => 'created',
        ]);
    }
}
