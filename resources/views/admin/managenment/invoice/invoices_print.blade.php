@extends('admin.admin_dashboard')

@section('content')
    <div class="container mt-5">
        <div class="mb-4 text-center">
            <h3>Customer Detail</h3>
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
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
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
                                $currentStock = \App\Models\BuyProduct::where('product_id', $item->product_id)->sum('quantity');
                            @endphp
                            <tr>
                                <td>{{ $item->product->category ? $item->product->category->name : 'N/A' }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $currentStock }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->product->unit_price,0) }}</td>
                                <td>{{ number_format($item->quantity * $item->product->unit_price ,0)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No products found for this invoice.</p>
        @endif

        <!-- Amount Details -->
        <div class="mb-4">
            <h4>Amount Details</h4>
            <div class="row">
                <div class="col-md-3">
                    <p><strong>Payment status:</strong> {{ $invoice->payment_status }}</p>
                </div>
                <div class="col-md-3">
                    @foreach ($invoice->items as $item)
                        <p><strong>Discount:</strong> {{ number_format($item->discount,0) }}%</p>
                    @endforeach
                </div>
                <div class="col-md-3">
                    <p><strong>Paid Amount:</strong> {{ number_format($invoice->partial_amount,0) }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Due Amount:</strong> {{ number_format($invoice->total_price,0) }}</p>
                </div>
                <div class="col-md-3">
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
