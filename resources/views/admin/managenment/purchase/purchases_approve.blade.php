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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Purchase Date</th>
                        <th>Supplier</th>
                        <th>Category</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->created_at->format('Y-m-d') }}</td>
                            <td>{{ $purchase->supplier ? $purchase->supplier->name : 'N/A' }}</td>
                            <td>{{ $purchase->category ? $purchase->category->name : 'N/A' }}</td>
                            <td>
                                @foreach ($purchase->buyProducts as $buyProduct)
                                    <div>{{ $buyProduct->product ? $buyProduct->product->name : 'N/A' }}</div>
                                @endforeach
                            </td>
                            <td>{{ $purchase->sttaus }}</td>
                            <td>
                                @if ($purchase->sttaus === 'pending')
                                    <a class="btn btn-success btn-sm approve-btn" data-id="{{ $purchase->id }}">Approve</a>
                                @else
                                    <button class="btn btn-success btn-sm" disabled>Approved</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveButtons = document.querySelectorAll('.approve-btn');

            approveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const purchaseId = this.getAttribute('data-id');

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
                                url: '{{ route('purchases.approve') }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    purchase_id: purchaseId
                                },
                                success: function(response) {
                                    if (response.success) {
                                        toastr.success('Purchase approved successfully!');
                                        setTimeout(function() {
                                            window.location.href = '{{ route('purchases.index') }}';
                                        }, 2000);
                                    } else {
                                        toastr.error('Failed to approve purchase.');
                                    }
                                }
                            });
                        }
                    });
                });
            });
        });
    </script>
@endsection
