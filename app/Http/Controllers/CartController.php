<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function list()
    {
        if(Auth::check()){
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->with('cartItems')->first();
            $total_price = 0;
            $cartItem = CartItem::where('cart_id',$cart->id)->with(['productVariant.product', 'productVariant.color', 'productVariant.size'])->get();
            //dd($cartItem);
            
            foreach($cartItem as $item){
                //dd($item);
                $product_variant_id = $item->productVariant->id;
                
                $product_variant_items = ProductVariant::where('id',$product_variant_id)->get();
                //$product_info = Product::where()
                //dd($product_variant_items);
                foreach($product_variant_items as $product_variants){
                    // dd($product_variant  s->product_id);
                    $product = Product::where('id',$product_variants->product_id)->get();
                    //dd($product);   
                }
                foreach ($product as $product_item) {
                    // Tính giá tiền cho từng sản phẩm
                    $price = $product_item->price_sale * $item->quantity;
                    // Cộng dồn vào tổng giá tiền
                    $total_price += $price;
                }
                
            }
            
            $coupon = session('coupon');
            if ($coupon) {
                $discountAmount = $this->calculateDiscount($total_price, $coupon);
                $total_price -= $discountAmount;
                $coupon['used'] += 1;
                $coupon->save();
            }
            if (!$cart || $cart->cartItems->isEmpty()|| $cart == null) {
                return redirect()->back()->with('error', 'Giỏ hàng trống');
            }
            return view('user.cart-list', compact('cart','product','total_price','user','cartItem'));
        }else{
            $message = 'Giỏ hàng trống';
            $user = Auth::user();
            $cart = session('cart') ?? $message ;
            //dd($cart);
            // $totalAmount = 0;
            // foreach ($cart as $item) {
            //     $totalAmount += $item['quantity'] * ($item['price_sale'] ?: $item['price_regular']);
            // }
            return view('user.cart-list',compact('cart','user','message'));
        }        
    }

    public function add(Request $request)
    {
        if (Auth::check()) {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);
            $user = Auth::user();
            $product = Product::query()->findOrFail(\request('product_id'));
            $productVariant = ProductVariant::query()
                ->with(['color', 'size'])
                ->where('product_id', \request('product_id'))
                ->where('size_id', \request('size_id'))
                ->where('color_id', \request('color_id'))
                ->where([
                    'product_id' => \request('product_id'),
                    'size_id' => \request('size_id'),
                    'color_id' => \request('color_id'),
                ])
                ->firstOrFail();
            //dd($productVariant);
            //Tìm hoặc tạo giỏ hàng cho người dùng
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            $cart_id = $cart->id;
            // dd($cart->id);
            //dd($request->quantity);
            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $productVariant->id)
                ->first();
                //dd($cartItem);
            if ($cartItem) {
                // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                // Nếu sản phẩm chưa có trong giỏ hàng, tạo mới CartItem
                CartItem::query()->create([
                    'cart_id' => $cart_id,
                    'product_variant_id' => $productVariant->id,
                    'quantity' => $request->quantity,
                ]);
            }
            return redirect()->route('cart.list')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng');
        } else {
            $product = Product::query()->findOrFail(\request('product_id'));
            $productVariant = ProductVariant::query()
                ->with(['color', 'size'])
                ->where('product_id', \request('product_id'))
                ->where('size_id', \request('size_id'))
                ->where('color_id', \request('color_id'))
                ->where([
                    'product_id' => \request('product_id'),
                    'size_id' => \request('size_id'),
                    'color_id' => \request('color_id'),
                ])
                ->firstOrFail();
            if (!isset(session('cart')[$productVariant->id])) {
                $data = $product->toArray()
                    + $productVariant->toArray()
                    + ['quantity' => \request('quantity')];

                session()->put('cart.' . $productVariant->id, $data);
            } else {
                $data = session('cart')[$productVariant->id];
                $data['quantity'] = \request('quantity');

                session()->put('cart.' . $productVariant->id, $data);
            }
            return redirect()->route('cart.list');
        }

    }
    public function applyCoupon(Request $request)
    {
        $couponCode = $request->input('coupon_code');
        //dd($couponCode);
        $coupon = Coupon::where('code', $couponCode)
                        ->where('is_active', true)
                        ->where('valid_from', '<=', now())
                        ->where('valid_to', '>=', now())
                        ->first();
        
        // if (!$coupon) {
        //     return back()->with('error','Mã giảm giá không hợp lệ');
        // }

        if ($coupon->usage_limit && $coupon->used >= $coupon->usage_limit) {
            return back()->with('error','Mã giảm giá đã hết lượt dùng');
        }

        // Lưu mã giảm giá vào session
        session(['coupon' => $coupon]);

        return back()->with('success','Áp dụng mã giảm giá thành công');
    }
    public function calculateDiscount($totalPrice, Coupon $coupon)
    {
        if ($coupon->type === 'fixed') {
            return $coupon->value;
        } else {
            return $totalPrice * ($coupon->value / 100);
        }
    }
}
