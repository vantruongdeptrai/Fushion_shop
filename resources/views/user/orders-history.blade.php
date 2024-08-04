@extends('user.layout.main')

@section('content')

@foreach($orders as $order)
    <div class="container p-5">
        <h1>Lịch sử đơn hàng</h1>
        <div class="card">
            <h5 class="card-header">Đơn hàng #{{ $order->id }}</h5>

            <div class="card-body">
                <h5 class="card-title">Thông tin</h5>
                <p>Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p>Tổng tiền: {{ number_format($order->total_price) }} VNĐ</p>
                <p>Trạng thái: {{ $order->status_order }}</p>
                <p>Thanh toán: {{ $order->status_payment }}</p>
                <h3>Sản phẩm:</h3>
                <ul>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Màu</th>
                                <th>Size</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>
                                        <div
                                            style="background-color:{{ $item->variant_color_name }};width: 40px; height: 20px;">
                                        </div>
                                    </td>
                                    <td>{{ $item->variant_size_name }}</td>
                                    <td>{{ number_format($item->product_price_sale ?? $item->product_price_regular) }}
                                        VNĐ
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </ul>
            </div>
        </div>
    </div>
@endforeach
{{ $orders->links() }}
@endsection