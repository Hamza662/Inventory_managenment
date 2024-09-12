@extends('admin.admin_dashboard')

@section('content')
    <style>
        .table th,
        .table td {
            text-align: center;
        }
    </style>

    <div class="card m-4">
        <h5 class="card-header">Generate Approved Invoice Report</h5>
        <div class="card-body">
            <form action="{{ route('invoices.generateReport') }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end" style="justify-content:right; margin-bottom:16px">
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </div>
                </div>
            </form>

            @if (isset($invoices))
                <table class="table table-bordered">
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
                            <!-- display invoice details here -->
                            @foreach ($invoice->items as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{ $invoice->date->format('d/m/Y') }}</td>
                                    <td>{{ $item->product ? $item->product->name : 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price,0) }}</td>
                                    <td>{{ number_format($item->total_price,0) }}</td>
                                </tr>
                                @php $grandTotal += $item->total_price; @endphp
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
                <a href="{{ route('invoices.generatePdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
                    class="btn btn-primary mt-3">
                     <i class="fas fa-file-pdf"></i> Generate PDF
                 </a>
                
            @endif
        </div>
    </div>
@endsection
