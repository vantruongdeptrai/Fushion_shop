<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\CartItem;
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
            $cartItem = CartItem::where('cart_id',$cart->id)->with('productVariant')->get();
            foreach($cartItem as $item){
                $product_variant_id = $item->productVariant->id;
                
                $product_variant_items = ProductVariant::where('id',$product_variant_id)->get();
                //$product_info = Product::where()
                foreach($product_variant_items as $product_variants){
                    // dd($product_variants->product_id);
                    $product = Product::where('id',$product_variants->product_id)->get();
                    //dd($product);   
                }
            }
            if (!$cart || $cart->cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Giỏ hàng trống');
            }
            return view('user.cart-list', compact('cart','product'));
        }else{
            $cart = session('cart');
            // $totalAmount = 0;
            // foreach ($cart as $item) {
            //     $totalAmount += $item['quantity'] * ($item['price_sale'] ?: $item['price_regular']);
            // }
            return view('user.cart-list');
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
}
