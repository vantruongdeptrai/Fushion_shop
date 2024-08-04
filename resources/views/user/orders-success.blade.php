@extends('user.layout.main')

@section('content')
<div class="container p-5">
    <h1 style="color:green;">Đặt hàng thành công</h1>
    <div class="card">
        <h5 class="card-header">Thông tin</h5>
        <div class="card-body">
            <h5 class="card-title">Mã đơn hàng: <strong>{{ $order->id }}</strong></h5>
            <p class="card-text">Tổng tiền: <strong>{{ number_format($order->total_price) }}</strong> VNĐ</p>
            <a href="{{ route('orders.history') }}" class="btn" style="background-color: #3F69AA; color:#FFF;">Xem lịch sử đơn hàng</a>
        </div>
    </div>
</div>
@endsection