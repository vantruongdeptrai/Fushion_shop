@extends('admin.dashboard')

@section('content')
    <h1>Chi tiết đơn hàng #{{ $order->id }}</h1>
    <h2>Thông tin khách hàng</h2>
    <p>Tên: {{ $order->user_name }}</p>
    <p>Email: {{ $order->user_email }}</p>
    <p>Số điện thoại: {{ $order->user_phone }}</p>
    <p>Địa chỉ: {{ $order->user_address }}</p>

    <h2>Trạng thái đơn hàng</h2>
    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-5">
                <select name="status_order" class="form-select ">
                    @foreach(\App\Models\Order::STATUS_ORDER as $key => $value)
                        <option value="{{ $key }}" {{ $order->status_order == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <select name="status_payment" class="form-select">
                    @foreach(\App\Models\Order::STATUS_PAYMENT as $key => $value)
                        <option value="{{ $key }}" {{ $order->status_payment == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div><br>
        <button type="submit" class="btn" style="background-color: #405189; color:#fff;">Cập nhật trạng thái</button>
    </form>
    <br>
    <h2>Chi tiết đơn hàng</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product_name }} ({{ $item->variant_size_name }}, {{ $item->variant_color_name }})</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->product_price_sale) }} VND</td>
                    <td>{{ number_format($item->quantity * $item->product_price_sale) }} VND</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Tổng cộng: {{ number_format($order->total_price) }} VND</p>
    
    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
        @csrf
        @method('DELETE')
        <button class="btn" type="submit" style="background-color: #405189;color:#fff;">Xóa đơn hàng</button>
    </form>
    <br>
    <h2>Hóa đơn</h2>
    <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-primary" target="_blank">In hóa đơn</a>
    
    <form action="{{ route('admin.orders.send-invoice', $order) }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-secondary">Gửi hóa đơn qua email</button>
    </form>
@endsection