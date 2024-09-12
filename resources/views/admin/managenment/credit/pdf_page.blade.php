<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Credit Customers PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
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
            font-size: 24px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://xufire.com/wp-content/uploads/2022/05/4.xufire.xufire-social-media-kit-1.png" alt="Xufire Logo">
            <h1>Xufire Inventory Management System</h1>
        </div>
        <h2>Credit Customers</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Invoice No</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Due Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($creditcustomers as $creditcustomer)
                    <tr>
                        <td>{{ $creditcustomer->id }}</td>
                        <td>{{ $creditcustomer->invoice }}</td>
                        <td>{{ $creditcustomer->date }}</td>
                        <td>{{ $creditcustomer->customer->name }}</td>
                        <td>${{ $creditcustomer->due_amount }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4">Grand Due Amount</td>
                    <td>${{ $grandtotal }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
