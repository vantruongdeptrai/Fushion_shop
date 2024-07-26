<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStock implements ShouldQueue 
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
        
        $orderItem = $event->orderItem;
        foreach ($orderItem as $item) {
            // Giả định item có thuộc tính 'product_name' và 'quantity'
            $product_name = $item->product_name;
            $quantity = $item->quantity;
            // Tìm sản phẩm trong kho và giảm số lượng
            DB::table('stocks')
                ->where('product_name', $product_name)
                ->decrement('quantity',1);
        }
    }
}
