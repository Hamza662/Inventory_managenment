@extends('admin.admin_dashboard')

@section('content')
    <div class="container mt-4">
        <h2>Invoices</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Invoice No</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Products</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->date)->format('Y-m-d') }}</td>
                        <td>{{ $invoice->customer ? $invoice->customer->name : 'N/A' }}</td>
                        <td>
                            @foreach ($invoice->items as $item)
                                {{ $item->product->name }}
                            @endforeach
                        </td>
                        <td>{{ $invoice->total_price }}</td>
                        <td>{{ $invoice->status }}</td>
                        <td>
                            @if ($invoice->status === 'pending')
                                <form id="delete-form-{{ $invoice->id }}"
                                    action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $invoice->id }});">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-success btn-sm" disabled>Approved</button>
                            @endif
                        </td>
                        <td>
                            @if ($invoice->status === 'approved')
                                <a href="{{ route('invoices.print', ['id' => $invoice->id]) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            {{ $invoices->links('pagination::bootstrap-5') }}
        </div>
    </div>
    <script>
        
    </script>
@endsection
