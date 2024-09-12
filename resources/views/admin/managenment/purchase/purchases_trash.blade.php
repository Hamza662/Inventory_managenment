@extends('admin.admin_dashboard')

@section('content')
    <style>
        .table th,
        .table td {
            text-align: center;
        }
    </style>

    <div class="card m-4">
        <h5 class="card-header">Purchase List</h5>
        <div class="card-body">
            {{-- <a href="{{ route('purchases.create') }}" class="btn btn-primary mb-3">Create Purchase</a> --}}
            <table class="table table-bordered">
                {{-- <pre>{{ print_r($purchases->toArray(), true) }}</pre> --}}

                <thead>
                    <tr>
                        {{-- <th>Purchase No</th> --}}
                        <th>Purchase Date</th>
                        <th>Supplier</th>
                        <th>Category</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                        {{-- <th>Description</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        @foreach ($purchase->buyProducts as $buyProduct)
                            <tr>
                                {{-- <td>{{ $purchase->purchase_no }}</td> --}}
                                <td>{{ $purchase->date }}</td>
                                <td>{{ $purchase->supplier ? $purchase->supplier->name : 'N/A' }}</td>
                                <td>{{ $purchase->category ? $purchase->category->name : 'N/A' }}</td>
                                <td>{{ $buyProduct->product ? $buyProduct->product->name : 'N/A' }}</td>
                                <td>{{ $buyProduct->quantity }}</td>
                                <td>{{ number_format($buyProduct->unit_price )}}</td>
                                <td>{{ number_format($buyProduct->total_price) }}</td>
                                <td>{{ $purchase->sttaus }}</td>
                                <td style="display: inline-flex;">
                                    <a href="{{ route('purchases.restore', $purchase->id) }}" class="btn btn-primary btn-sm" onclick="return confirmRestore({{ $purchase->id }});" style="margin-right: 2px">
                                        <i class="bx bx-undo"></i>
                                    </a>
                                                                
                                    <form id="delete-form-{{ $purchase->id }}" action="{{ route('purchases.forcedelete', $purchase->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $purchase->id }});">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                                {{-- <td>{{ $buyProduct->description }}</td> --}}
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
