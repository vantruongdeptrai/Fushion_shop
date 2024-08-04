@extends('user.layout.main')
@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Products</a></li>
            <li class="breadcrumb-item active">Cart</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->


<!-- Cart Start -->
<div class="cart-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{session('success')}}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{session('error')}}
                        </div>
                    @endif
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>

                                <th>Price sale</th>
                                <th>Color</th>
                                <th>Size</th>

                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            @if (session()->has('cart'))
                                @foreach (session('cart') as $item)
                                    <tr>
                                        <td><a href="#"><img src="" alt="Image"></a></td>
                                        <td><a href="#">{{$item['name']}}</a></td>

                                        <td>{{ $item['price_sale'] }}</td>
                                        <td>{{ $item['color']['name'] }}</td>
                                        <td>{{ $item['size']['name'] }}</td>
                                        <td>
                                            <div class="qty">
                                                <button class="btn-minus"><i class="fa fa-minus"></i></button>
                                                <input type="text" value="1">
                                                <button class="btn-plus"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </td>
                                        <td>$22</td>
                                        <td><button><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                @endforeach
                            @endif
                            @php
                                $totalPrice = 0;
                            @endphp
                            @foreach ($cart->cartItems as $item)
                                <tr>
                                    <td><a href="#"><img src="{{asset($item->productVariant->image)}}" alt="Image" width="60" height="60"></a></td>
                                    @foreach ($product as $product_item)
                                        <td><a href="#">{{$product_item->name}}</a></td>
                                        <td>{{ number_format($product_item->price_sale) }} VNĐ</td>
                                    @endforeach
                                    <td>{{ $item->productVariant->color->name }}</td>
                                    <td>{{ $item->productVariant->size->name }}</td>
                                    <td>
                                        <div class="qty">
                                            <button class="btn-minus"><i class="fa fa-minus"></i></button>
                                            <input type="text" value="{{$item->quantity}}">
                                            <button class="btn-plus"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </td>
                                    @php
                                        // Tính giá tiền cho từng sản phẩm
                                        $price = $product_item->price_sale * $item->quantity;
                                        // Cộng dồn vào tổng giá tiền
                                        $totalPrice += $price;
                                    @endphp
                                    @foreach ($product as $product_item)
                                        <td>{{ number_format($product_item->price_sale * $item->quantity) }} VNĐ</td>
                                    @endforeach
                                    <td><button><i class="fa fa-trash"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="coupon">
                    <input type="text" placeholder="Coupon Code">
                    <button>Apply Code</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="cart-summary">
                    <div class="cart-content">
                        <h3>Cart Summary</h3>
                        <p>Sub Total<span>{{number_format($totalPrice)}} VNĐ</span></p>
                        <p>Shipping Cost<span>0 VNĐ</span></p>
                        <h4>Grand Total<span>{{number_format($totalPrice)}} VNĐ</span></h4>
                    </div>
                    <div class="cart-btn">
                        <button>Update Cart</button>
                        <a href="{{route('checkout')}}"><button>Check out</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart End -->

@endsection