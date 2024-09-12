@extends('admin.admin_dashboard')
@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Stock Report</h1>

        {{-- <p class="mb-4">Today is Thursday, August 8, 2024 and here are the results:</p> --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Supplier Name</th>
                        <th>Unit</th>
                        <th>Category Name</th>
                        <th>Product Name</th>
                        <th>Total Quantity</th>
                        <th>Sold Quantity</th>
                        <th>Remaining Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        @php
                            // Calculate the current stock for this product (sum of all purchased quantities)
                            $currentStock = \App\Models\BuyProduct::where('product_id', $product->id)->sum('quantity');

                            // Calculate the total sold quantity for this product from approved invoices
                            $soldQuantity = $product
                                ->items()
                                ->whereHas('invoice', function ($query) {
                                    $query->where('status', 'approved');
                                })
                                ->sum('quantity');

                            // Calculate the remaining stock
                            $remainingStock = $currentStock - $soldQuantity;
                        @endphp

                        <tr>
                            <td>{{ $product->supplier->name }}</td>
                            <td>{{ $product->unit->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $currentStock }}</td>
                            <td>{{ $soldQuantity }}</td>
                            <td>{{ $remainingStock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
