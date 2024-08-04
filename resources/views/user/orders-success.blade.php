@extends('layouts.app')

@section('content')
    <h1>Đặt hàng thành công</h1>
    <p>Mã đơn hàng: {{ $order->id }}</p>
    <p>Tổng tiền: {{ number_format($order->total_price) }} VNĐ</p>
    <a href="{{ route('orders.history') }}">Xem lịch sử đơn hàng</a>
@endsection