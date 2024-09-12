<!DOCTYPE html>
<html>

<head>
    <title>Invoice Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 200px;
            height: auto;
        }

        .header h1 {
            margin: 0;
        }

        .date-range {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="https://xufire.com/wp-content/uploads/2022/05/4.xufire.xufire-social-media-kit-1.png" alt="Xufire Logo">
        <h1>Xufire Inventory Management System</h1>
    </div>

    <div class="date-range">
        <p><strong>Report Period:</strong> {{ $startDate }} to {{ $endDate }}</p>
    </div>

    <h2>Invoice Report</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($invoices as $invoice)
                @foreach ($invoice->items as $item)
                    @php
                        $totalPrice = $item->quantity * $item->price;
                        $grandTotal += $totalPrice;
                    @endphp
                    <tr>
                        <td>{{ $invoice->id }}</td>
                        <td>{{ $invoice->date->format('d/m/Y') }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $totalPrice }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align: right;">Grand Total:</th>
                <th>${{ $grandTotal }}</th>
            </tr>
        </tfoot>
    </table>

</body>

</html>
