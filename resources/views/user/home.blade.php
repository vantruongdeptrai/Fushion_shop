@extends('user.layout.main')
@section('content')


<!-- Main Slider Start -->
<div class="home-slider">
    <div class="main-slider">
        <div class="main-slider-item"><img src="{{asset('theme/client/img/slider-1.jpg')}}" alt="Slider Image" />
        </div>
        <div class="main-slider-item"><img src="{{asset('theme/client/img/slider-2.jpg')}}" alt="Slider Image" />
        </div>
        <div class="main-slider-item"><img src="{{asset('theme/client/img/slider-3.jpg')}}" alt="Slider Image" />
        </div>
    </div>
</div>
<!-- Main Slider End -->
<!-- Feature Start-->
<div class="feature">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-6 feature-col">
                <div class="feature-content">
                    <i class="fa fa-shield"></i>
                    <h2>Trusted Shopping</h2>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 feature-col">
                <div class="feature-content">
                    <i class="fa fa-shopping-bag"></i>
                    <h2>Quality Product</h2>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 feature-col">
                <div class="feature-content">
                    <i class="fa fa-truck"></i>
                    <h2>Worldwide Delivery</h2>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 feature-col">
                <div class="feature-content">
                    <i class="fa fa-phone"></i>
                    <h2>Telephone Support</h2>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Feature End-->


<!-- Category Start-->
<div class="category">
    <div class="container-fluid">
        <div class="row">
            @foreach ($catelogues as $item)
                <div class="col-md-3">
                    <div class="category-img">
                        <img src="{{Storage::url($item->cover)}}" />
                        <a class="category-name" href="">
                            <h2>{{$item->name}}</h2>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Category End-->


<!-- Featured Product Start -->
<div class="featured-product">
    <div class="container">
        <div class="section-header">
            <h3>Hot Deal Product</h3>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec viverra at massa sit amet ultricies.
                Nullam consequat, mauris non interdum cursus
            </p>
        </div>
        <div class="row align-items-center product-slider product-slider-4">
            @foreach ($products as $item)
            <div class="col-lg-3">
                <div class="product-item">
                    <div class="product-image">
                        <a href="product-detail.html">
                            <img src="{{Storage::url($item->img_thumbnail)}}" alt="Product Image">
                        </a>
                        <div class="product-action">
                            <a href="#"><i class="fa fa-cart-plus"></i></a>
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
                        <div class="price" style="font-size: 15px;">{{$item->price_regular}} VNĐ<span>{{$item->price_sale}} VNĐ</span></div>
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
    </div>
</div>
<!-- Featured Product End -->


<!-- Newsletter Start -->
<div class="newsletter">
    <div class="container">
        <div class="section-header">
            <h3>Subscribe Our Newsletter</h3>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec viverra at massa sit amet ultricies.
                Nullam consequat, mauris non interdum cursus
            </p>
        </div>
        <div class="form">
            <input type="email" value="Your email here">
            <button>Submit</button>
        </div>
    </div>
</div>
<!-- Newsletter End -->


<!-- Recent Product Start -->
<div class="recent-product">
    <div class="container">
        <div class="section-header">
            <h3>New Product</h3>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec viverra at massa sit amet ultricies.
                Nullam consequat, mauris non interdum cursus
            </p>
        </div>
        <div class="row align-items-center product-slider product-slider-4">
        @foreach ($products as $item)
            <div class="col-lg-3">
                <div class="product-item">
                    <div class="product-image">
                        <a href="product-detail.html">
                            <img src="{{Storage::url($item->img_thumbnail)}}" alt="Product Image">
                        </a>
                        <div class="product-action">
                            <a href="#"><i class="fa fa-cart-plus"></i></a>
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
                        <div class="price" style="font-size: 15px;">{{$item->price_regular}} VNĐ<span>{{$item->price_sale}} VNĐ</span></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Recent Product End -->


<!-- Brand Start -->
<div class="brand">
    <div class="container">
        <div class="section-header">
            <h3>Our Brands</h3>
            
        </div>
        <div class="brand-slider">
            <div class="brand-item"><img src="{{asset('theme/client/img/brand-1.png')}}" alt=""></div>
            <div class="brand-item"><img src="{{asset('theme/client/img/brand-2.png')}}" alt=""></div>
            <div class="brand-item"><img src="{{asset('theme/client/img/brand-3.png')}}" alt=""></div>
            <div class="brand-item"><img src="{{asset('theme/client/img/brand-4.png')}}" alt=""></div>
            <div class="brand-item"><img src="{{asset('theme/client/img/brand-5.png')}}" alt=""></div>
            <div class="brand-item"><img src="{{asset('theme/client/img/brand-6.png')}}" alt=""></div>
        </div>
    </div>
</div>
<!-- Brand End -->
@endsection