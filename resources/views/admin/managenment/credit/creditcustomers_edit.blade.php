@extends('admin.admin_dashboard')

@section('content')
    <div class="container">
        <div class="col-xl">
            <div class="card m-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Update Invoice</h5>
                    <small class="text-muted float-end">Merged Customer</small>
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
                                        {{-- <th rowspan="2">Category</th> --}}
                                        <th rowspan="2">Product</th>
                                        <th rowspan="2">Current Stock</th>
                                        <th rowspan="2">Quantity</th>
                                        <th rowspan="2">Unit Price</th>
                                        <th rowspan="2">Total Price</th>
                                    </tr>
                                    <tr>
                                        <th>Discount</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoiceItems as $item)
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
                                            <td>${{ number_format($item->invoice->partial_amount) }}</td>
                                            <td>${{ number_format($dueAmount) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No items found for this invoice.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Status, Date, Partial Amount, and Update Section -->
                    <form action="{{ route('invoices.update', $invoice->id) }}" id="invoiceUpdateForm" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mt-4 d-flex align-items-center justify-content-between">
                            <!-- Payment Status Dropdown -->
                            <div class="form-group me-3">
                                <label for="payment_status" class="form-label">Payment Status</label>
                                <select class="form-select" id="payment_status" name="payment_status"
                                    onchange="togglePartialAmount()">
                                    <option value="full_paid"
                                        {{ $invoice->payment_status == 'full_paid' ? 'selected' : '' }}>Full Paid</option>
                                    <option value="partial_paid"
                                        {{ $invoice->payment_status == 'partial_paid' ? 'selected' : '' }}>Partial Paid
                                    </option>
                                </select>
                                @error('payment_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Partial Amount Input (Hidden by Default) -->
                            <div class="form-group me-3" id="partial_amount_field" style="display: none;">
                                <label for="partial_amount" class="form-label">Partial Amount</label>
                                <input type="number" class="form-control" id="partial_amount" name="partial_amount">
                                @error('partial_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Date Picker -->
                            <div class="form-group me-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date">
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Update Button -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePartialAmount() {
            const paymentStatus = document.getElementById('payment_status').value;
            const partialAmountField = document.getElementById('partial_amount_field');

            if (paymentStatus === 'partial_paid') {
                partialAmountField.style.display = 'block';
            } else {
                partialAmountField.style.display = 'none';
            }
        }

        // Trigger the function on page load to handle preselected option
        document.addEventListener('DOMContentLoaded', function() {
            togglePartialAmount();
        });
    </script>
@endsection
