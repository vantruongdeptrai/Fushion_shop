@extends('user.layout.main')
@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Products</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->


<!-- Checkout Start -->
<form action="{{route('order.save')}}" method="post">
    @csrf
    <div class="checkout">
        <div class="container">
            <div class="row">
                <div class="col-7">
                    <div class="">
                        <div class="billing-address">
                            <h2>Billing Address</h2>
                            <div class="row">
                                <div class="col-12">
                                    <label>Full Name</label>
                                    <input class="form-control" type="text" placeholder="First Name" name="user_name"
                                        id="user_name" value="{{ auth()->user()?->name }}">
                                </div>
                                <div class="col-12">
                                    <label>E-mail</label>
                                    <input class="form-control" type="text" placeholder="E-mail" name="user_email"
                                        id="user_email" value="{{ auth()->user()?->email }}">
                                </div>
                                <div class="col-12">
                                    <label>Phone number</label>
                                    <input class="form-control" type="text" placeholder="Phone Number" name="user_phone"
                                        id="user_phone">
                                </div>
                                <div class="col-12">
                                    <label>Address</label>
                                    <input class="form-control" type="text" placeholder="Address" name="user_address"
                                        id="user_address">
                                </div>

                                <div class="col-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="newaccount">
                                        <label class="custom-control-label" for="newaccount">Create an account</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="shipto">
                                        <label class="custom-control-label" for="shipto">Ship to different
                                            address</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="shipping-address">
                            <h2>Shipping Address</h2>
                            <div class="row">
                                @csrf
                                <div class="col-12">
                                    <label>First Name</label>
                                    <input class="form-control" type="text" placeholder="First Name">
                                </div>
                                <div class="col-12">
                                    <label>Last Name"</label>
                                    <input class="form-control" type="text" placeholder="Last Name">
                                </div>
                                <div class="col-12">
                                    <label>E-mail</label>
                                    <input class="form-control" type="text" placeholder="E-mail">
                                </div>
                                <div class="col-12">
                                    <label>Mobile No</label>
                                    <input class="form-control" type="text" placeholder="Mobile No">
                                </div>
                                <div class="col-12">
                                    <label>Address</label>
                                    <input class="form-control" type="text" placeholder="Address">
                                </div>
                                <div class="col-12">
                                    <label>Country</label>
                                    <select class="custom-select">
                                        <option selected>United States</option>
                                        <option>Afghanistan</option>
                                        <option>Albania</option>
                                        <option>Algeria</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label>City</label>
                                    <input class="form-control" type="text" placeholder="City">
                                </div>
                                <div class="col-12">
                                    <label>State</label>
                                    <input class="form-control" type="text" placeholder="State">
                                </div>
                                <div class="col-12">
                                    <label>ZIP Code</label>
                                    <input class="form-control" type="text" placeholder="ZIP Code">
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="checkout-payment">
                            <h2>Payment Methods</h2>
                            <div class="payment-methods">
                                <div class="payment-method">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="payment-1" name="payment">
                                        <label class="custom-control-label" for="payment-1">Paypal</label>
                                    </div>

                                </div>
                                <div class="payment-method">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="payment-2" name="payment">
                                        <label class="custom-control-label" for="payment-2">Payoneer</label>
                                    </div>

                                </div>

                                <div class="payment-method">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="payment-4" name="payment">
                                        <label class="custom-control-label" for="payment-4">Direct Bank Transfer</label>
                                    </div>

                                </div>
                                <div class="payment-method">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="payment-5" name="payment">
                                        <label class="custom-control-label" for="payment-5">Cash on Delivery</label>
                                    </div>

                                </div>
                            </div>
                            <div class="checkout-btn">
                                <button type="submit">Place Order</button>
                            </div>
                        </div>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{session('success')}}
                            </div>
                        @endif
                        @if(session('error'))
                        <br>
                            <div class="alert alert-danger">
                                {{session('error')}}
                            </div>
                        @endif
                        @if ($errors->any())
                        <br>
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-5">
                    <div class="row">
                        <div class="checkout-summary">
                            <div class="checkout-content" style="color:#FFF;">
                                <h3>Products</h3>
                                @php
                                    $totalPrice = 0;
                                @endphp
                                @foreach ($cart->cartItems as $item)
                                    <div>
                                        <img src="{{asset($item->productVariant->image)}}" alt="Image" width="60"
                                            height="60">
                                        @foreach ($product as $product_item)
                                            Name: <span>{{$product_item->name}}</span>
                                        @endforeach
                                        Color: <span>{{ $item->productVariant->color->name}}</span>
                                        Size: <span>{{ $item->productVariant->size->name }}</sp>
                                            @php
                                                // Tính giá tiền cho từng sản phẩm
                                                $price = $product_item->price_sale * $item->quantity;
                                                // Cộng dồn vào tổng giá tiền
                                                $totalPrice += $price;
                                            @endphp
                                            @foreach ($product as $product_item)
                                                <br>Price:
                                                <span>{{ number_format($product_item->price_sale * $item->quantity) }}
                                                    VNĐ</span>
                                            @endforeach
                                    </div><br>
                                    <hr>
                                @endforeach

                                @if (session()->has('cart'))
                                    @foreach (session('cart') as $item)
                                        <p>{{$item['name']}} <span>{{$item['price_regular']}}</span></p>
                                    @endforeach
                                @endif
                                <p>Sub Total<span>{{number_format($totalPrice)}} VNĐ</span></p>
                                <p>Shipping Cost<span>0 VNĐ</span></p>
                                <h4>Grand Total<span>{{number_format($totalPrice)}} VNĐ</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Checkout End -->
@endsection