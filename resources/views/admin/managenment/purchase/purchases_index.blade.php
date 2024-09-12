@extends('admin.admin_dashboard')

@section('content')
    <style>
        .table th,
        .table td {
            text-align: center;
        }

        /* Allow vertical scrolling but prevent horizontal overflow */
        body,
        html {
            overflow-x: hidden;
            /* Prevent horizontal overflow */
            overflow-y: auto;
            /* Allow vertical scrolling */
        }

        /* Ensure table container scrolls horizontally if necessary */
        .purchase {
            overflow-x: auto;
            max-height: 80vh;
            /* width: max-content; */
            /* Limit height of the purchase div and allow vertical scroll within it if needed */
        }
    </style>

    <div class="card m-4 purchase">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header mb-0">
                Purchase List
            </h5>
            {{-- <div class="input-group search" style="max-width: 300px;">
                <input type="text" name="search" id="search" class="form-control" placeholder="Search supplier..."
                    style="margin-right:15px">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i> <!-- Font Awesome search icon -->
                    </button>
                </div>
            </div> --}}
        </div>
        <div class="card-body">
            <a href="{{ route('purchases.create') }}" class="btn btn-primary mb-3">Create Purchase</a>
            <table class="table table-bordered" style="width: max-content">
                <thead>
                    <tr>
                        <th>Purchase Date</th>
                        <th>Supplier</th>
                        <th>Category</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="alldata">
                    @foreach ($purchases as $purchase)
                        @php
                            $rowCount = $purchase->buyProducts->count();
                        @endphp
                        <tr>
                            <td rowspan="{{ $rowCount }}">{{ $purchase->created_at->format('d M, Y') }}</td>
                            <td rowspan="{{ $rowCount }}">{{ $purchase->supplier ? $purchase->supplier->name : 'N/A' }}</td>
                            <td rowspan="{{ $rowCount }}">{{ $purchase->category ? $purchase->category->name : 'N/A' }}</td>
                            <td>{{ $purchase->buyProducts[0]->product ? $purchase->buyProducts[0]->product->name : 'N/A' }}</td>
                            <td>{{ $purchase->buyProducts[0]->quantity }}</td>
                            <td>{{ number_format($purchase->buyProducts[0]->unit_price,0) }}</td>
                            <td>{{ number_format($purchase->buyProducts[0]->total_price,0) }}</td>
                            <td rowspan="{{ $rowCount }}">{{ $purchase->sttaus }}</td>
                            <td rowspan="{{ $rowCount }}">
                                @if ($purchase->sttaus === 'pending')
                                    <form id="delete-form-{{ $purchase->id }}" action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $purchase->id }});">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-success btn-sm" disabled>Approved</button>
                                @endif
                            </td>
                        </tr>
                        @for ($i = 1; $i < $rowCount; $i++)
                            <tr>
                                <td>{{ $purchase->buyProducts[$i]->product ? $purchase->buyProducts[$i]->product->name : 'N/A' }}</td>
                                <td>{{ $purchase->buyProducts[$i]->quantity }}</td>
                                <td>{{ $purchase->buyProducts[$i]->unit_price }}</td>
                                <td>{{ $purchase->buyProducts[$i]->total_price }}</td>
                            </tr>
                        @endfor
                    @endforeach
                </tbody>
                <tbody id="content" class="searchdata"></tbody>
            </table>
        </div>
    </div>
    <div>
        {{ $purchases->links('pagination::bootstrap-5') }}
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#search').on('keyup', function() {
            var $value = $(this).val();
            if ($value) {
                $('.alldata').hide();
                $('.searchdata').show();
            } else {
                $('.alldata').show();
                $('.searchdata').hide();
            }
            $.ajax({
                type: 'get',
                url: '/search',
                data: {
                    'search': $value
                },
                success: function(data) {
                    console.log(data);
                    $('#content').html(data);
                }
            });
        });
    </script>
@endsection
