<?php

namespace App\Listeners;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Events\OrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotification implements ShouldQueue
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
        $data = array('name'=>"Virat Gandhi");
        Mail::send('mail', $data, function($message) {
            $message->to('dovantruong033@gmail.com', 'Tutorials Point')->subject
               ('Laravel Basic Testing Mail');
            
         });
         echo "Basic Email Sent. Check your inbox.";
    }
}
