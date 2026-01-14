<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .order-details {
            background-color: white;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .total-row {
            font-weight: bold;
            font-size: 1.2em;
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Thank You for Your Order!</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $order->customer->name }},</p>
        
        <p>Thank you for your order. We're pleased to confirm that we've received your order and it's being processed.</p>
        
        <div class="order-details">
            <h2>Order Details</h2>
            <p><strong>Order Number:</strong> #{{ $order->order_id }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
        </div>
        
        <h2>Order Items</h2>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>type</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['product_type'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>EGP{{ number_format($item['price'] ) }}</td>
                    <td>EGP{{ number_format($item['subtotal']) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total:</td>
                    <td>EGP{{ number_format($totalAmount) }}</td>
                </tr>
            </tbody>
        </table>
        
        <p>If you have any questions about your order, please don't hesitate to contact us.</p>
        
        <p>Best regards,<br>
        {{ config('app.name') }}</p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. Please do not reply directly to this message.</p>
    </div>
</body>
</html>