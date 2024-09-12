@extends('admin.admin_dashboard')

@section('content')
    <div class="container">
        <div class="col-xl">
            <div class="card m-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Invoice Details</h5>
                    <small class="text-muted float-end">Customer Information</small>
                </div>
                <div class="card-body">
                    <!-- Invoice ID and Customer Details Section -->
                    <div class="mb-4">
                        <h6>Invoice ID: <strong>{{ $invoice->id }}</strong></h6>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Customer Name:</label>
                                <p>{{ $invoice->customer ? $invoice->customer->name : 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email:</label>
                                <p>{{ $invoice->customer ? $invoice->customer->email : 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Address:</label>
                                <p>{{ $invoice->customer ? $invoice->customer->address : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Details Table -->
                    <div class="mt-4">
                        <h6>Invoice Details</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Current Stock</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total Price</th>
                                        <th>Discount</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoice->items as $item)
                                        @php
                                            $currentStock = \App\Models\BuyProduct::where(
                                                'product_id',
                                                $item->product_id,
                                            )->sum('quantity');

                                            $soldQuantity = \App\Models\Item::where('product_id', $item->product_id)
                                                ->whereHas('invoice', function ($query) {
                                                    $query->where('status', 'approved');
                                                })
                                                ->sum('quantity');

                                            $remainingStock = $currentStock - $soldQuantity;

                                            $partialAmount = $item->invoice->partial_amount;

                                            $dueAmount = $item->total_price - $item->invoice->partial_amount;
                                        @endphp
                                        <tr>
                                            <td>{{ optional($item->product)->name }}</td>
                                            <td>{{ $remainingStock }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->price) }}</td>
                                            <td>${{ number_format($item->total_price) }}</td>
                                            <td>{{ number_format($item->discount) }}%</td>
                                            <td>${{ number_format($invoice->partial_amount) }}</td>
                                            <td>${{ number_format($dueAmount ,0)}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No items found for this invoice.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paid Summary Section -->
                    <!-- Paid Summary Section -->
                    {{-- <div class="mt-4">
                        <h6>Paid Summary</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Payment Date</th>
                                        <th>Amount Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date->format('d-m-Y') }}</td>
                                            <td>${{ number_format($payment->amount) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">No payments recorded for this invoice.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><strong>Total Paid:</strong></td>
                                        <td>${{ number_format($payments->sum('amount')) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>


                    <!-- Invoice Creation Date -->
                    <div class="mt-4">
                        <h6>Invoice Creation Date</h6>
                        <p>{{ $invoice->created_at->format('d-m-Y') }}</p>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
