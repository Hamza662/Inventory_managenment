@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            Product Trashed
            {{-- <a href="{{ route('suppliers.trash') }}" class="btn btn-danger btn-sm">GoTo Trash</a> --}}
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">Back to view</a>
        </h5>
        <div class="table-responsive text-nowrap">
            <table class="table" style="text-align:center">
                <thead class="table-light">
                    <tr>
                        <th>Sr</th>
                        <th>supplier Name</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Unit Price</th>
                        <th>Unit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->supplier->name }}</td>
                            <td>{{ $product->category ? $product->category->name : 'N/A' }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{number_format($product->unit_price)}}</td>
                            <td>{{ $product->unit->name }}</td>
                            <td>
                                <a href="{{ route('products.restore', $product->id) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="bx bx-edit-alt"></i> Restore
                                </a>
                                <form id="delete-form-{{ $product->id }}" action="{{ route('products.forcedelete', $product->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $product->id }});">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
