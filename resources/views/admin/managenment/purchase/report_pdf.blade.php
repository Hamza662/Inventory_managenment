<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #f2f2f2;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 150px;
            height: auto;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
            text-align: right;
        }

        h2 {
            text-align: center;
            color: #555;
            margin-top: 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #f2f2f2;
            color: #333;
        }

        .total {
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="https://xufire.com/wp-content/uploads/2022/05/4.xufire.xufire-social-media-kit-1.png" alt="Xufire Logo">
        <h1>Xufire Inventory Management System</h1>
    </div>

    <h2>Approved Purchase Report</h2>

    <p><strong>Date Range:</strong> {{ request('start_date') }} to {{ request('end_date') }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>Purchase No</th>
                <th>Date</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($purchases as $purchase)
                @foreach ($purchase->buyProducts as $buyProduct)
                    <tr>
                        <td>{{ $purchase->purchase_no }}</td>
                        <td>{{ $purchase->date }}</td>
                        <td>{{ $buyProduct->product ? $buyProduct->product->name : 'N/A' }}</td>
                        <td>{{ $buyProduct->quantity }}</td>
                        <td>{{ number_format($buyProduct->unit_price, 0) }}</td>
                        <td>{{ number_format($buyProduct->total_price, 0) }}</td>
                    </tr>
                    @php $grandTotal += $buyProduct->total_price; @endphp
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="total">Grand Total:</td>
                <td class="total">${{ number_format($grandTotal, 0) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}
    </div>

</body>

</html>
