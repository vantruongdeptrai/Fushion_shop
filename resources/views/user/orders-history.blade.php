@extends('layouts.app')

@section('content')
    <h1>Lịch sử đơn hàng</h1>
    @foreach($orders as $order)
        <div>
            <h2>Đơn hàng #{{ $order->id }}</h2>
            <p>Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p>Tổng tiền: {{ number_format($order->total_price) }} VNĐ</p>
            <p>Trạng thái: {{ $order->status_order }}</p>
            <p>Thanh toán: {{ $order->status_payment }}</p>
            <h3>Sản phẩm:</h3>
            <ul>
                @foreach($order->orderItems as $item)
                    <li>
                        {{ $item->product_name }} - {{ $item->variant_color_name }} - {{ $item->variant_size_name }}
                        ({{ $item->quantity }} x {{ number_format($item->product_price_sale ?? $item->product_price_regular) }} VNĐ)
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
    {{ $orders->links() }}
@endsection