<?php

namespace App\Http\Controllers;
use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{
    //
    public function index(){
        $cart = session('cart');
        return view('user.check-out',compact('cart'));
    }
    public function save()
    {
        try {
            DB::transaction(function () {
                $user = User::query()->create([
                    'name' => \request('user_name'),
                    'email' => \request('user_email'),
                    'password' => bcrypt(\request('user_password')), // Giả sử có đầu vào mật khẩu
                    'is_active' => false,
                ]);
        
                $totalAmount = 0;
                $dataItem = [];
                
                if (!session()->has('cart')) {
                    throw new \Exception('Giỏ hàng trống');
                }
        
                foreach (session('cart') as $variantID => $item) {
                    $totalAmount += $item['quantity'] * ($item['price_sale'] ?: $item['price_regular']);
                    $dataItem[] = [
                        'product_variant_id' => $variantID,
                        'quantity' => $item['quantity'], // Đã sửa lỗi chính tả
                        'product_name' => $item['name'],
                        'product_sku' => $item['sku'],
                        'product_img_thumbnail' => $item['img_thumbnail'],
                        'product_price_regular' => $item['price_regular'],
                        'product_price_sale' => $item['price_sale'],
                        'variant_size_name' => $item['size']['name'],
                        'variant_color_name' => $item['color']['name'],
                    ];
                }
                
                $order = Order::query()->create([
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_phone' => \request('user_phone'),
                    'user_address' => \request('user_address'),
                    'total_price' => $totalAmount,
                ]);
        
                foreach ($dataItem as $item) {
                    $item['order_id'] = $order->id;
                    OrderItem::query()->create($item);
                }
                $orderItem = OrderItem::where('order_id', $order->id)->get();
                //dd($order->user_name);
                event(new OrderCreated($order,$orderItem));
            });

            session()->forget('cart');
            
            return redirect()->route('user.home')->with('success', 'Đặt hàng thành công');
        } catch (\Exception $exception) {
            return back()->with('error', 'Lỗi đặt hàng');
        }
        
    }
}
