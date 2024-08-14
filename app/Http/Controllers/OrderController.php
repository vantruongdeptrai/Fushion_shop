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
            $coupon = session('coupon');
            $total_price = $this->totalPrice();
            if ($coupon) {
                $discountAmount = $this->cartController->calculateDiscount($total_price, $coupon);
                $total_price -= $discountAmount;
                $coupon['used'] += 1;
                $coupon->save();
            }
            session()->forget('coupon');
            return view('user.check-out', compact('cart', 'product','total_price'));
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
                'user_phone' => 'required|string|max:11',
                'user_address' => 'required|string|max:255',
            ]);

            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->with('cartItems')->first();

            if (!$cart || $cart->cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Giỏ hàng trống');
            }

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
    private function redirectToMoMo(Order $order)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'YOUR_PARTNER_CODE';
        $accessKey = 'YOUR_ACCESS_KEY';
        $secretKey = 'YOUR_SECRET_KEY';
        $orderInfo = "Thanh toán đơn hàng " . $order->id;
        $amount = $order->total_price;
        $orderId = $order->id . time(); // Tạo một orderId duy nhất
        $redirectUrl = route('momo.return');
        $ipnUrl = route('momo.ipn');
        $extraData = "";

        $requestId = time() . "";
        $requestType = "captureWallet";
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        $response = Http::post($endpoint, $data);
        $responseData = $response->json();

        if ($responseData['resultCode'] == 0) {
            // Lưu thông tin giao dịch
            Transaction::create([
                'order_id' => $order->id,
                'transaction_id' => $orderId,
                'amount' => $amount,
                'status' => 'pending',
            ]);

            return redirect($responseData['payUrl']);
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo giao dịch MoMo');
    }

    public function momoReturn(Request $request)
    {
        // Xử lý kết quả trả về từ MoMo
        if ($request->resultCode == '0') {
            $orderId = $request->orderId;
            $transactionId = $request->transId;
            
            $transaction = Transaction::where('transaction_id', $orderId)->first();
            if ($transaction) {
                $transaction->status = 'completed';
                $transaction->save();

                $order = Order::find($transaction->order_id);
                $order->status_payment = Order::STATUS_PAYMENT_PAID;
                $order->save();

                return redirect()->route('order.success', $order->id);
            }
        }

        return redirect()->route('order.failure');
    }

    public function momoIPN(Request $request)
    {
        // Xử lý IPN (Instant Payment Notification) từ MoMo
        $secretKey = 'YOUR_SECRET_KEY'; // Cùng secret key ở trên

        // Kiểm tra chữ ký
        $rawHash = "accessKey=" . $request->accessKey . "&amount=" . $request->amount . "&extraData=" . $request->extraData . "&ipnUrl=" . $request->ipnUrl . "&orderId=" . $request->orderId . "&orderInfo=" . $request->orderInfo . "&partnerCode=" . $request->partnerCode . "&redirectUrl=" . $request->redirectUrl . "&requestId=" . $request->requestId . "&requestType=" . $request->requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        if ($signature != $request->signature) {
            return response()->json([
                'message' => 'Invalid signature',
            ], 400);
        }
        if ($request->resultCode == '0') {
            $orderId = $request->orderId;
            $transactionId = $request->transId;
            
            $transaction = Transaction::where('transaction_id', $orderId)->first();
            if ($transaction) {
                $transaction->status = 'completed';
                $transaction->save();

                $order = Order::find($transaction->order_id);
                $order->status_payment = Order::STATUS_PAYMENT_PAID;
                $order->save();
            }
        }

        return response()->json([
            'message' => 'IPN processed successfully',
        ]);
    }
}
