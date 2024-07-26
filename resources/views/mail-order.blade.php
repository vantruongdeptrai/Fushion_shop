<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
</head>

<body>
    <h1>Xác nhận đơn hàng</h1>

    <p>Xin chào {{ $order->user_name }},</p>

    <p>Đơn hàng của bạn đã được đặt thành công. Chi tiết đơn hàng:</p>

    <ul>
        <li>Mã đơn hàng: {{ $order->id }}</li>
        <li>Tổng giá trị: {{ $order->total }}</li>
        <!-- Thêm các thông tin khác của đơn hàng tại đây -->
    </ul>

    <p>Cảm ơn bạn đã mua hàng!</p>
</body>

</html>