@extends('admin.admin_dashboard')

@section('content')
    <div class="container" style="margin-top: 10px;">
        <h2>Credit Customers Data</h2>

        <!-- Invoices Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Invoices</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Product Name</th>
                            <th>Date</th>
                            <th>Payment Status</th>
                            <th>Due Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            @php
                                $item = $invoice->items->first();

                                if ($item) {
                                    $due_amount = $item->total_price - $invoice->partial_amount;
                                } else {
                                    $due_amount = 'N/A';
                                }
                            @endphp
                            <tr>
                                <td>{{ $invoice->customer ? $invoice->customer->name : 'N/A' }}</td>
                                <td>
                                    @php
                                        $productNames = $invoice->items->pluck('product.name')->join(', ');
                                    @endphp
                                    {{ $productNames }}
                                </td>
                                <td>{{ $invoice->date->format('d/m/Y') }}</td>
                                <td>{{ $invoice->payment_status }}</td>
                                <td>
                                    @if ($invoice->payment_status === 'partial_paid')
                                        {{ number_format($due_amount) }}
                                    @else
                                        --
                                    @endif
                                </td>

                                <td>
                                    @if ($invoice->payment_status === 'partial_paid')
                                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('creditcustomers.show', $invoice->id) }}" class="btn btn-danger btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div>
        {{$invoices->links('pagination::bootstrap-5')}}
    </div>
@endsection
