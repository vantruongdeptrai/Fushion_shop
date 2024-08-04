<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->with('cartItems')->first();
            $cartItem = CartItem::where('cart_id', $cart->id)->with('productVariant')->get();
            foreach ($cartItem as $item) {
                $product_variant_id = $item->productVariant->id;

                $product_variant_items = ProductVariant::where('id', $product_variant_id)->get();
                //$product_info = Product::where()
                foreach ($product_variant_items as $product_variants) {
                    // dd($product_variants->product_id);
                    $product = Product::where('id', $product_variants->product_id)->get();
                    //dd($product);   
                }
            }
            return view('user.check-out', compact('cart','product'));
        } else {
            $cart = session('cart');
            return view('user.check-out', compact('cart'));
        }
        
    }
    public function save(Request $request)
    {
        if (Auth::check()) {
            $validatedData = $request->validate([
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|max:255',
                'user_phone' => 'required|string|max:20',
                'user_address' => 'required|string|max:255',
                'payment' => 'required|string',
            ]);

            $user = auth()->user();
            $cart = Cart::where('user_id', $user->id)->with('cartItems.productVariant.product')->first();

            if (!$cart || $cart->cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Giỏ hàng trống');
            }

            DB::beginTransaction();

            try {
                // Create order
                $order = new Order();
                $order->user_id = $user->id;
                $order->fill($validatedData);
                $order->total_price = $cart->cartItems->sum(function ($item) {
                    return $item->quantity * ($item->productVariant->product->price_sale ?? $item->productVariant->product->price_regular);
                });
                $order->save();

                // Create order items
                foreach ($cart->cartItems as $cartItem) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_variant_id = $cartItem->product_variant_id;
                    $orderItem->quantity = $cartItem->quantity;
                    $orderItem->product_name = $cartItem->productVariant->product->name;
                    $orderItem->product_sku = $cartItem->productVariant->product->sku;
                    $orderItem->product_img_thumbnail = $cartItem->productVariant->product->thumbnail;
                    $orderItem->product_price_regular = $cartItem->productVariant->product->price_regular;
                    $orderItem->product_price_sale = $cartItem->productVariant->product->price_sale;
                    $orderItem->variant_size_name = $cartItem->productVariant->size->name;
                    $orderItem->variant_color_name = $cartItem->productVariant->color->name;
                    $orderItem->save();
                }

                // Clear cart
                CartItem::where('cart_id', $cart->id)->delete();
                $cart->delete();

                DB::commit();

                // Redirect to payment gateway or show success message
                return redirect()->route('payment.process', $order->id);

            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Có lỗi xảy ra trong quá trình đặt hàng');
            }
        } else {
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
                    event(new OrderCreated($order, $orderItem));
                });

                session()->forget('cart');

                return redirect()->route('user.home')->with('success', 'Đặt hàng thành công');
            } catch (\Exception $exception) {
                return back()->with('error', 'Lỗi đặt hàng');
            }
        }

    }
    public function processPayment(Order $order)
    {
        // Implement payment gateway integration here
        // For example, redirect to a payment service or process payment locally

        // After successful payment:
        $order->status_payment = Order::STATUS_PAYMENT_PAID;
        $order->save();

        return redirect()->route('order.success', $order->id);
    }

    public function success(Order $order)
    {
        return view('orders.success', compact('order'));
    }

    public function history()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->with('orderItems')->orderBy('created_at', 'desc')->paginate(10);
        return view('orders.history', compact('orders'));
    }
}
