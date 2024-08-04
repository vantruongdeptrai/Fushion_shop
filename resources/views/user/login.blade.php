@extends('user.layout.main')
@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">User</a></li>
            <li class="breadcrumb-item active">Login</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->


<!-- Login Start -->
<div class="login">
    <div class="container">
        <div class="section-header">
            <h3>Login</h3>

        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="register-form">
                    <form action="{{route('user.post-login')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label>E-mail</label>
                                <input class="form-control" type="text" placeholder="E-mail" name="email">
                            </div>
                            <div class="col-md-6">
                                <label>Password</label>
                                <input class="form-control" type="text" placeholder="Password" name="password">
                            </div>
                            <div class="col-md-12">
                                <button class="btn">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
    </div>
</div>
<!-- Login End -->

@endsection