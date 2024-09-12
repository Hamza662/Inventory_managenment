@extends('admin.admin_dashboard')

@section('content')
    <div class="container mt-4">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            All Trashed
            <a href="{{ route('invoices.index') }}" class="btn btn-primary btn-sm">Back To view</a>
        </h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    {{-- <th>Invoice No</th> --}}
                    <th>Date</th>
                    <th>Supplier</th>
                    <th>Product</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        {{-- <td>{{ $invoice->id }}</td> --}}
                        <td>{{ $invoice->date->format('Y-m-d') }}</td>
                        <td>{{ $invoice->supplier->name }}</td>
                        <td>
                            @foreach ($invoice->items as $item)
                                {{ $item->product->name }}
                            @endforeach
                        </td>
                        <td>{{ number_format($invoice->total_price) }}</td>

                        <td>
                            <a href="{{ route('invoices.restore', $invoice->id) }}" class="btn btn-primary btn-sm" onclick="return confirmRestore({{ $invoice->id }});">
                                <i class="bx bx-undo"></i>
                            </a>
                                                        
                            <form id="delete-form-{{ $invoice->id }}" action="{{ route('invoices.forcedelete', $invoice->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $invoice->id }});">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- <div>
            {{ $invoices->links('pagination::bootstrap-5') }}
        </div> --}}
    </div>
@endsection
