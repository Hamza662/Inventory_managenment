@extends('admin.admin_dashboard')

@section('content')

    <style>
        /* Custom styles for print */
        @media print {
            .no-print {
                display: none;
            }

            .container {
                max-width: 100% !important;
                padding: 0 !important;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
            }

            .header img {
                max-width: 200px;
                height: auto;
            }

            .btn {
                display: none;
                /* Hide buttons */
            }
        }

        body {
            font-family: 'Helvetica', sans-serif;
        }
    </style>

    <div class="container mt-5">
        <!-- Header with Image and Title -->
        <div class="header text-center mb-4">
            <img src="https://xufire.com/wp-content/uploads/2022/05/4.xufire.xufire-social-media-kit-1.png" alt="Xufire Logo"
                style="max-width: 200px; height: auto;">
            <h1>Xufire Inventory Management System</h1>
        </div>

        <h3>Customer Detail:</h3>
        <div class="mb-4">
            <h3>Xufire Shopping Mall</h3>
            <h4>Xufire@gmail.com</h4>
            <h6>Multan</h6>
        </div>

        <div class="mb-4">
            <h4>Customer Information</h4>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Name:</strong> {{ $invoice->customer ? $invoice->customer->name : 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Email:</strong> {{ $invoice->customer ? $invoice->customer->email : 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Address:</strong> {{ $invoice->customer ? $invoice->customer->address : 'N/A' }}</p>
                </div>
            </div>
        </div>

        @if ($invoice->items->isNotEmpty())
            <div class="mb-4">
                <h4>Invoice Products</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Product Name</th>
                            <th>Current Stock</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $item)
                            @php
                                $currentStock = \App\Models\BuyProduct::where('product_id', $item->product_id)->sum(
                                    'quantity',
                                );
                            @endphp
                            <tr>
                                <td>{{ $item->product->category ? $item->product->category->name : 'N/A' }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $currentStock }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->product->unit_price }}</td>
                                <td>{{ $item->quantity * $item->product->unit_price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No products found for this invoice.</p>
        @endif

        <div class="mb-4">
            <h4>Amount Details</h4>
            <div class="row">
                <div class="col-md-4">
                    @foreach ($invoice->items as $item)
                        <p><strong>Discount:</strong> {{ number_format($item->discount,0) }}%</p>
                    @endforeach
                </div>
                <div class="col-md-4">
                    <p><strong>Paid Amount:</strong> {{ number_format($invoice->partial_amount,0) }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Due Amount:</strong> {{ number_format($invoice->due_amount,0) }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Grand Total:</strong> {{ number_format($invoice->total_price,0) }}</p>
                </div>
            </div>
        </div>

        {{-- <a href="{{ route('download_pdf', $invoice->id) }}" class="btn btn-primary no-print">
            Download PDF
            <i class="fa-regular fa-file-pdf"></i>
        </a> --}}

    </div>

@endsection
