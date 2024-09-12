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
                    <th>Product</th>
                    <th>Total Amount</th>
                    <th>Status</th>
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
                            @php
                                $productNames = $invoice->items->pluck('product.name')->join(', ');
                            @endphp
                            {{ $productNames }}
                        </td>
                        
                        <td>{{ number_format($invoice->total_price ,0) }}</td>
                        <td>{{ $invoice->status }}</td>
                        <td>
                            {{-- @if ($invoice->status === 'pending')
                                <a class="btn btn-success btn-sm approve-btn" data-id="{{ $invoice->id }}">Approve</a>
                            @else
                                <button class="btn btn-success btn-sm" disabled>Approved</button>
                            @endif --}}
                            <a href="{{route('invoices.show',$invoice->id)}}" class="btn btn-sm btn-success">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- {{ $invoices->links('pagination::bootstrap-5') }} --}}
    </div>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveButtons = document.querySelectorAll('.approve-btn');

            approveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const invoiceId = this.data('id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to approve this purchase?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, approve it!',
                        cancelButtonText: 'No, cancel!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to approve the purchase
                            $.ajax({
                                url:'/invoices/approve/' + invoiceId,
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    invoice_id: invoiceId
                                },
                                success: function(response) {
                                    if (response.success) {
                                        toastr.success(
                                            'Invoice approved successfully!'
                                        );
                                        setTimeout(function() {
                                            window.location.href =
                                                '{{ route('invoices.index') }}';
                                        }, 2000);
                                    } else {
                                        toastr.error(
                                            'Failed to approve invoice.');
                                    }
                                }
                            });
                        }
                    });
                });
            });
        });
    </script> --}}
     <div>
        {{$invoices->links('pagination::bootstrap-5')}}
    </div>
@endsection
