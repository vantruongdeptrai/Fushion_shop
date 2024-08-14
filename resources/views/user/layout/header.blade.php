<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Fushion Shop</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce Template" name="keywords">
    <meta content="Bootstrap Ecommerce Template Free Download" name="description">

    <!-- Favicon -->
    <link href="{{asset('theme/client/img/favicon.ico')}}" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap" rel="stylesheet">

    <!-- CSS Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{asset('theme/client/lib/slick/slick.css')}}" rel="stylesheet">
    <link href="{{asset('theme/client/lib/slick/slick-theme.css')}}" rel="stylesheet">
    <style>
        .buy-button {
            display: inline-block;
            background-color: #3F69AA;
            color: white;
            padding: 11px 20px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .buy-button:hover {
            border: 1px solid #3F69AA;
            background-color: white;
            color: #3F69AA;
        }

        .buy-button i {
            margin-right: 5px;
        }

        .buy-button:hover i {
            color: #3F69AA;
        }
    </style>
    <!-- Template Stylesheet -->
    <link href="{{asset('theme/client/css/style.css')}}" rel="stylesheet">
    <script src="{{asset('theme/client/js/main.js')}}"></script>
</head>

<body>
    <!-- Top Header Start -->
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <div class="logo">
                        <a href="">
                            <img src="{{asset('theme/client/img/logo.png')}}" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="search">
                        <input type="text" placeholder="Search">
                        <button><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="user">
                        @auth
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Xin Chào
                                    {{Auth::user()->name}}</a>
                                <div class="dropdown-menu">
                                    <a href="{{route('my-account')}}" class="dropdown-item">My Account</a>
                                    <form id="logout-form" action="{{ route('user.logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        <button type="submit">Logout</button>
                                    </form>
                                    
                                </div>
                            </div>
                        @else
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account</a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('user.login') }}" class="dropdown-item">Login</a>
                                    <a href="{{ route('user.register') }}" class="dropdown-item">Register</a>
                                </div>
                            </div>
                        @endauth
                        <div class="cart">
                            <a href="{{route('cart.list')}}"><i class="fa fa-cart-plus"></i></a>
                            <span>(0)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Top Header End -->


    <!-- Header Start -->
    <div class="header">
        <div class="container">
            <nav class="navbar navbar-expand-md bg-dark navbar-dark">
                <a href="#" class="navbar-brand">MENU</a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav m-auto">
                        <a href="{{route('home')}}" class="nav-item nav-link active">Home</a>
                        <a href="{{route('product-list')}}" class="nav-item nav-link">Products</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu">
                                <a href="product-list.html" class="dropdown-item">Product</a>
                                <a href="product-detail.html" class="dropdown-item">Product Detail</a>
                                <a href="cart.html" class="dropdown-item">Cart</a>
                                <a href="wishlist.html" class="dropdown-item">Wishlist</a>
                                <a href="checkout.html" class="dropdown-item">Checkout</a>
                                <a href="my-account.html" class="dropdown-item">My Account</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Contact Us</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Header End -->