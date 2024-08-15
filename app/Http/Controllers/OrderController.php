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
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
class OrderController extends Controller
{   public $cartController;
    public function __construct(){
        $this->cartController = new CartController();
    }
    //
    public function totalPrice(){
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->with('cartItems')->first();
            $cartItem = CartItem::where('cart_id', $cart->id)->with('productVariant')->get();
            $total_price = 0;
            foreach ($cartItem as $item) {
                $product_variant_id = $item->productVariant->id;

                $product_variant_items = ProductVariant::where('id', $product_variant_id)->get();
                //$product_info = Product::where()
                foreach ($product_variant_items as $product_variants) {
                    // dd($product_variants->product_id);
                    $product = Product::where('id', $product_variants->product_id)->get();
                    //dd($product);   
                }
                foreach ($product as $product_item) {
                    // Tính giá tiền cho từng sản phẩm
                    $price = $product_item->price_sale * $item->quantity;
                    // Cộng dồn vào tổng giá tiền
                    $total_price += $price;
                }
            }
            return $total_price;
        }
    }
    public function index()
    {

        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->with('cartItems')->first();
            $cartItem = CartItem::where('cart_id',$cart->id)->with(['productVariant.product', 'productVariant.color', 'productVariant.size'])->get();
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
            $coupon = session('coupon');
            $total_price = $this->totalPrice();
            if ($coupon) {
                $discountAmount = $this->cartController->calculateDiscount($total_price, $coupon);
                $total_price -= $discountAmount;
                $coupon['used'] += 1;
                $coupon->save();
            }
            session()->forget('coupon');
            return view('user.check-out', compact('cart', 'product','total_price','cartItem'));
        } else {
            $cart = session('cart');
            return view('user.check-out', compact('cart'));
        }
    }
    public function save(Request $request)
    {
        if (Auth::check()) {
            //dd($request->all());
            $validatedData = $request->validate([
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|max:255',
                'user_phone' => 'required|string|max:11',
                'user_address' => 'required|string|max:255',
            ]);
            
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->with('cartItems')->first();

            if($request->payment == 'i_bank'){
                return redirect()->route('vnpay');
            }else{
                DB::beginTransaction();
                try {
                    // Create order
                    
                    $order = new Order();
                    $order->user_id = $user->id;
                    $order->user_name = $validatedData['user_name'];
                    $order->user_email = $validatedData['user_email'];
                    $order->user_phone = $validatedData['user_phone'];
                    $order->user_address = $validatedData['user_address'];
                    $order->status_order = Order::STATUS_ORDER_PENDING;
                    $order->status_payment = Order::STATUS_PAYMENT_UNPAID;
                    $order->total_price = $this->totalPrice();
                    //dd($order->total_price);
                    $order->save();
    
                    $cartItem = CartItem::where('cart_id', $cart->id)->with('productVariant')->get();
                    
                    foreach ($cartItem as $item) {
                        $product_variant_id = $item->productVariant->id;
                        $product_variant_items = ProductVariant::where('id', $product_variant_id)->get();
                        //$product_info = Product::where()
                        foreach ($product_variant_items as $product_variants) {
                            // dd($product_variants->product_id);
                            $product = Product::where('id', $product_variants->product_id)->get();
                            //dd($product);   
                            // Create order items
                            $orderItem = new OrderItem();
                            
                            $orderItem->order_id = $order->id;
                            $orderItem->product_variant_id = $product_variant_id;
                            $orderItem->quantity = $item->quantity;
                            foreach($product as $product_item){
                                $orderItem->product_name = $product_item->name;
                                $orderItem->product_sku = $product_item->sku;
                                $orderItem->product_img_thumbnail = $product_item->thumbnail;
                                $orderItem->product_price_regular = $product_item->price_regular;
                                $orderItem->product_price_sale = $product_item->price_sale;
                            }
                            $orderItem->variant_size_name = $item->productVariant->size->name;
                            $orderItem->variant_color_name = $item->productVariant->color->name;
                            
                            $orderItem->save();
                        }
                    }
                    // Clear cart
                    
                    CartItem::where('cart_id', $cart->id)->delete();
                    $cart->delete();
    
                    DB::commit();
                    // Redirect to payment gateway or show success message
                    //return redirect()->route('payment.process', $order->id);
                    return $this->redirectToVNPay($order);
                } catch (\Exception $e) {
                    DB::rollback();
                    return back()->with('error', 'Có lỗi xảy ra trong quá trình đặt hàng');
                }
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
        return view('user.orders-success', compact('order'));
    }

    public function history()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->with('orderItems')->orderBy('created_at', 'desc')->paginate(10);
        return view('user.orders-history', compact('orders'));
    }
    public function redirectToVNPay(Order $order)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = "HSKYRL0D";
        $vnp_HashSecret = "WC3GP5NHLZE23I5EG3VHOAMRX6JJ0OI6";

        $vnp_TxnRef = $order->id;
        $vnp_OrderInfo = "Thanh toán đơn hàng " . $order->id;
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $order->total_price * 100;
        $vnp_Locale = "VN";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        if ($request->vnp_ResponseCode == '00') {
            $orderId = $request->vnp_TxnRef;
            $order = Order::findOrFail($orderId);
            
            Transaction::create([
                'order_id' => $orderId,
                'transaction_id' => $request->vnp_TransactionNo,
                'amount' => $request->vnp_Amount / 100,
                'status' => 'completed',
            ]);

            $order->status_payment = Order::STATUS_PAYMENT_PAID;
            $order->save();

            return redirect()->route('order.success', $orderId);
        }

        return redirect()->route('order.failure');
    }
}
