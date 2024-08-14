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
                            @if(!$user)
                                <div class="alert alert-danger">
                                    {{$message}}
                                </div>
                            @else
                                @php
                                    $totalPrice = 0;
                                @endphp
                                
                                @foreach ($cartItem as $item)
                                    <tr>
                                        <td><a href="#"><img src="{{ Storage::url($item->productVariant->image)}}" alt="Image" width="60" height="60"></a></td>
                                        <td><a href="#">{{$item->productVariant->product->name}}</a></td>
                                        <td>{{ number_format($item->productVariant->product->price_sale) }} VNĐ</td>
                                        <td><div style="width: 30px; height:15px; background-color: {{ $item->productVariant->color->name }};"></div></td>
                                        <td>{{ $item->productVariant->size->name }}</td>
                                        <td>
                                            <div class="qty">
                                                <button class="btn-minus"><i class="fa fa-minus"></i></button>
                                                <input type="text" value="{{$item->quantity}}">
                                                <button class="btn-plus"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </td>
                                        @php
                                            $price = $item->productVariant->product->price_sale * $item->quantity;
                                            $totalPrice += $price;
                                        @endphp
                                        <td>{{ number_format($price) }} VNĐ</td>
                                        <td>
                                            <form action="#" method="post" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm khỏi giỏ hàng ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if ($user)
        <div class="row">
            <div class="col-md-6">
                <div class="coupon">
                    <form action="{{route('coupon')}}" method="post">
                        @csrf
                        <input type="text" placeholder="Coupon Code" name="coupon_code">
                        <button type="submit" >Apply Code</button>
                    </form>
                    
                </div>
            </div>
            <div class="col-md-6">
                <div class="cart-summary">
                    <div class="cart-content">
                        <h3>Cart Summary</h3>
                        <p>Sub Total<span>{{number_format($total_price)}} VNĐ</span></p>
                        <p>Shipping Cost<span>0 VNĐ</span></p>
                        
                        <h4>Grand Total<span>{{number_format($total_price)}} VNĐ</span></h4>
                    </div>
                    <div class="cart-btn">
                        <button>Update Cart</button>
                        <a href="{{route('checkout')}}"><button>Check out</button></a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
    </div>
</div>
<!-- Cart End -->

@endsection