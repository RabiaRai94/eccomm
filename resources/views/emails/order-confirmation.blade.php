<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation</title>
</head>

<body>
    <h1>Thank you for your order!</h1>
    <p>Hello {{ $productOrder->user->name ?? 'Valued Customer' }},</p>

    <p>We're excited to inform you that your order has been confirmed. Below are the details of your order:</p>

    <table class="order-details" border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Order ID</th>
            <td>{{ $productOrder->id }}</td>
        </tr>
        <tr>
            <th>Total Price</th>
            <td>${{ number_format($productOrder->total_price, 2) }}</td>
        </tr>
        <tr>
            <th>Payment Status</th>
            <td>{{ $productOrder->status }}</td>
        </tr>
    </table>

    <h3>Items Ordered:</h3>
    <ul>
        @foreach($productOrder->orderItems as $item)
            <li>
                {{ $item->product->name }} - Quantity: {{ $item->quantity }} - Price: ${{ number_format($item->price, 2) }}
            </li>
        @endforeach
    </ul>
    <p>If you have any questions or concerns, feel free to contact us.</p>
    <p>Thank you for shopping with us!</p>
</body>

</html>
