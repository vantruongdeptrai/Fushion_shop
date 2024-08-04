<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn đơn hàng #{{ $order->id }}</title>
</head>
<body>
    <h1>Cảm ơn bạn đã đặt hàng</h1>
    <p>Xin chào {{ $order->user_name }},</p>
    <p>Đơn hàng #{{ $order->id }} của bạn đã được xử lý. Vui lòng kiểm tra file đính kèm để xem hóa đơn chi tiết.</p>
    <p>Tổng giá trị đơn hàng: {{ number_format($order->total_price) }} VND</p>
    <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>
    <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
</body>
</html>