@extends('user.layout.main')
@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Products</a></li>
            <li class="breadcrumb-item active">product list</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->


<!-- Product List Start -->
<div class="product-view">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="product-search">
                                    <input type="email" value="Search">
                                    <button><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="product-short">
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Product short by</a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item">Newest</a>
                                            <a href="#" class="dropdown-item">Popular</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Danh sách sản phẩm -->
                    @foreach ($list_product as $item)
                        <div class="col-lg-4">
                            <div class="product-item">
                                <div class="product-image">
                                    <a href="{{route('product.detail', $item->slug)}}">
                                        @php
                                            $url = $item->img_thumbnail;

                                            if (!\Str::contains($url, 'http')) {
                                                $url = \Illuminate\Support\Facades\Storage::url($url);
                                            }
                                        @endphp
                                        <img src="{{ $url }}" alt="Cover Image">
                                    </a>
                                    <div class="product-action">
                                        <a href="{{route('cart.list')}}">
                                            <i class="fa fa-cart-plus"></i>
                                        </a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                        <a href="#"><i class="fa fa-search"></i></a>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <div class="title"><a href="#">{{$item->name}}</a></div>
                                    <div class="ratting">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <div class="price" style="font-size: 20px;">{{$item->price_sale}}
                                        VNĐ<span>{{$item->price_regular}} VNĐ</span></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Phân trang -->
                <div class="col-lg-12 mt-4" style="text-align: center;">
                    {{$list_product->links()}}
                </div>
                
            </div>
            <div class="col-md-3">
                <div class="sidebar-widget category">
                    <h2 class="title">Category</h2>
                    <ul>
                        @foreach ($catelogues as $cate)
                            <li><a href="#">{{ $cate->name }}</a></li>
                        @endforeach
                        
                    </ul>
                </div>


                <div class="sidebar-widget tag">
                    <h2 class="title">Tags Cloud</h2>
                    @foreach ($tags as $tag)
                        <a href="#">{{ $tag->name }}</a>
                    @endforeach
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Product List End -->
@endsection
<!-- Breadcrumb Start -->