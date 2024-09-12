@extends('admin.admin_dashboard')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <div class="card m-4">
        <div class="d-flex justify-content-between" style="align-items: center; padding-right:25px;">
            <h5 class="card-header" style="margin-bottom:auto;">Invoice Details</h5>
            <p><strong>Date:</strong> {{ $invoice->date->format('Y-m-d') }}</p>
        </div>

        <div class="card-body">
            <!-- Invoice Information -->
            <div class="row mb-3">
                <div class="col-lg-12">
                    <!-- You can add additional information here if needed -->
                </div>
            </div>

            <!-- Customer Information -->
            <div class="mb-3">
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

            <!-- Invoice Products Table -->
            @if ($invoice->items->isNotEmpty())
                <table class="table">
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

                                $totalprice = $item->quantity * $item->product->unit_price;
                                $dueamount = $item->invoice->total_price - $invoice->partial_amount;
                            @endphp
                            <tr>
                                <td>{{ $item->product->category ? $item->product->category->name : 'N/A' }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $currentStock }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->product->unit_price ,0) }}</td>
                                <td>{{ number_format($totalprice,0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No products found for this invoice.</p>
            @endif

            <!-- Amounts -->
            <div class="mb-3">
                <h4 class="mt-3">Amount Details</h4>
                <div class="row">
                    <div class="col-md-4">
                        @foreach ($invoice->items as $item)
                            @if ($item->discount > 0)
                                <p><strong>Discount :</strong> {{ number_format($item->discount,0) }}%</p>
                            @else
                                <p><strong>Discount :</strong> No discount</p>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-md-4">
                        <p><strong>Payment Status:</strong> {{ $invoice->payment_status }}</p>
                    </div>

                    @if (in_array($invoice->payment_status, ['partial_paid', 'full_due']))
                        <div class="col-md-4">
                            <p><strong>Paid Amount:</strong> {{ number_format($invoice->partial_amount, 0) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Due Amount:</strong> {{ number_format($dueamount, 0) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Grand Amount:</strong> {{ number_format($dueamount ,0) }}</p>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('invoices.approvalPage') }}" class="btn btn-secondary">Back to Invoices</a>
                @if ($invoice->status === 'pending')
                    <button class="btn btn-success btn-sm approve-btn" data-id="{{ $invoice->id }}">Approve</button>
                @else
                    <button class="btn btn-success btn-sm" disabled>Approved</button>
                @endif
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.approve-btn').click(function() {
                var invoiceId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to approve this invoice?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/invoices/approve/' + invoiceId,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Approved!',
                                        'The invoice has been approved.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    toastr.error(response.message);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>

@endsection
