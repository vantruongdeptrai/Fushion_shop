<?php

namespace App\Listeners;

use App\Notifications\OrderCreatedNotification;
use App\Events\OrderCreated;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\OrderConfirmation;


class SenderOrderNotification implements ShouldQueue
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
        Log::debug(__CLASS__, [$event->order]);
        $order = $event->order;
        $data = array('order' => $order, 'name' => $order->user_name);
        Mail::send('mail-order', $data, function($message) use ($order) {
            $message->to($order->user_email, $order->user_name)
                    ->subject('Đặt hàng thành công');
        });
    }
}
