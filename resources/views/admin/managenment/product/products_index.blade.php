@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header mb-0">
                Product Management
            </h5>
            <div class="input-group search" style="max-width: 300px;">
                <input type="text" name="search" id="search" class="form-control" placeholder="Search product..."
                    style="margin-right:15px">
                <div class="input-group-append">
                    {{-- <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i> <!-- Font Awesome search icon -->
                    </button> --}}
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table" style="text-align:center">
                <thead class="table-light">
                    <tr>
                        <th>Sr</th>
                        <th>Name</th>
                        <th>Unit Price</th>
                        <th>supplier Name</th>
                        <th>Unit</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0 alldata">
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ number_format($product->unit_price,0) }}</td>
                            <td>{{ $product->supplier->name }}</td>
                            <td>{{ $product->unit->name }}</td>
                            <td>{{ $product->category ? $product->category->name : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <form id="delete-form-{{ $product->id }}"
                                    action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $product->id }});">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tbody id="content" class="searchdata"></tbody>
            </table>
        </div>
    </div>
    <div>
        {{ $products->links('pagination::bootstrap-5') }}
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
