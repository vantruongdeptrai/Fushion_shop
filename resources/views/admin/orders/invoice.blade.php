<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->id }}</title>
    <style> 
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        .header,
        .footer {
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Order #{{ $order->id }}</h1>
        </div>
        <div class="content">
            <p>Date: <strong>{{ $order->created_at->format('d/m/Y') }}</strong></p>

            <h2>Information customer</h2>
            <p>Name: <strong>{{ $order->user_name }}</strong></p>
            <p>Email: <strong>{{ $order->user_email }}</strong></p>
            <p>Phone: <strong>{{ $order->user_phone }}</strong></p>
            <p>Address: <strong>{{ $order->user_address }}</strong></p>
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
                            <td>{{ $item->product_name }} ({{ $item->variant_size_name }}, {{ $item->variant_color_name }})
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->product_price_sale) }} VND</td>
                            <td>{{ number_format($item->quantity * $item->product_price_sale) }} VND</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Tổng cộng: <strong>{{ number_format($order->total_price) }}</strong> VND</p>
        </div>
        <div class="footer">
            <h1>Cảm ơn quý khách đã mua hàng ^^ </h1>
        </div>
    </div>
</body>

</html>