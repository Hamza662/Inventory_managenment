@extends('admin.admin_dashboard')

@section('content')
    <div class="container">
        {{-- <h2>Search Results</h2> --}}

        {{-- Detail for the customers --}}
        @if ($customers->isNotEmpty())
            <h2>Customer : </h2>
            <div class="table-responsive text-nowrap">
                <div class="table-responsive">
                    <table class="table table-striped" style="text-align:center">
                        <thead class="table-light">
                            <tr>
                                <th>Sr</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Photo</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0 alldata">
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->address }}</td>
                                    <td>
                                        <div class="mb-3">
                                            <label for="exampleInputPassword1" class="form-label"> </label>
                                            <img class="w-px-40 h-auto rounded-circle"
                                                src="{{ !empty($customer->photo) ? url('image/admin_images/' . $customer->photo) : url('image/blank_image.jpg') }}"
                                                alt="profile" id="showImage">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <form id="delete-form-{{ $customer->id }}"
                                            action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $customer->id }});">
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
        @endif

        {{-- Detail for the Supplier --}}
        @if ($suppliers->isNotEmpty())
            <h2>Supplier : </h2>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped" style="text-align:center">
                    <thead class="table-light">
                        <tr>
                            <th>Sr</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0 alldata">
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <form id="delete-form-{{ $supplier->id }}"
                                        action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $supplier->id }});">
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
        @endif

        {{-- Detail for the Products --}}
        @if ($products->isNotEmpty())
            <h2>Product : </h2>
            <table class="table table-striped" style="text-align:center">
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
                            <td>{{ number_format($product->unit_price, 0) }}</td>
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
        @endif

        {{-- Detail for the Categories --}}
        @if ($categories->isNotEmpty())
            <h2>Category : </h2>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped" style="text-align:center">
                    <thead class="table-light">
                        <tr>
                            <th>Sr</th>
                            <th>Name</th>
                            <th>Supplier Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0 alldata">
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->supplier ? $category->supplier->name : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <form id="delete-form-{{ $category->id }}"
                                        action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $category->id }});">
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
        @endif

        
        {{-- Purchase related detail --}}
        @if ($purchases->isNotEmpty())
        <h3>Purchasing details : </h3>
            <table class="table table-bordered table-striped" style="width: max-content">
                <thead>
                    <tr>
                        <th>Purchase Date</th>
                        <th>Supplier</th>
                        {{-- <th>Category</th> --}}
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        @php
                            $rowCount = $purchase->buyProducts->count();
                        @endphp
                        <tr>
                            <td rowspan="{{ $rowCount }}">{{ $purchase->created_at->format('d M, Y') }}</td>
                            <td rowspan="{{ $rowCount }}">
                                {{ $purchase->supplier ? $purchase->supplier->name : 'N/A' }}
                            </td>
                            {{-- <td rowspan="{{ $rowCount }}">
                                {{ $purchase->buyProducts[0]->category ? $purchase->buyProducts[0]->category->name : 'N/A' }}
                            </td> --}}
                            <td>{{ $purchase->buyProducts[0]->product ? $purchase->buyProducts[0]->product->name : 'N/A' }}
                            </td>
                            <td>{{ $purchase->buyProducts[0]->quantity }}</td>
                            <td>{{ number_format($purchase->buyProducts[0]->unit_price, 0) }}</td>
                            <td>{{ number_format($purchase->buyProducts[0]->total_price, 0) }}</td>
                            <td rowspan="{{ $rowCount }}">{{ $purchase->sttaus }}</td>
                            <td rowspan="{{ $rowCount }}">
                                @if ($purchase->sttaus === 'pending')
                                    <form id="delete-form-{{ $purchase->id }}"
                                        action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $purchase->id }});">
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
                                <td>{{ $purchase->buyProducts[$i]->product ? $purchase->buyProducts[$i]->product->name : 'N/A' }}
                                </td>
                                <td>{{ $purchase->buyProducts[$i]->quantity }}</td>
                                <td>{{ number_format($purchase->buyProducts[$i]->unit_price, 0) }}</td>
                                <td>{{ number_format($purchase->buyProducts[$i]->total_price, 0) }}</td>
                            </tr>
                        @endfor
                    @endforeach
                </tbody>
            </table>

            <!-- Display pagination links -->
            {{ $purchases->appends(['query' => request()->input('query')])->links('pagination::bootstrap-5') }}
            @if (
                $customers->isEmpty() &&
                    $suppliers->isEmpty() &&
                    $products->isEmpty() &&
                    $categories->isEmpty() &&
                    $purchases->isEmpty())
                <p>No results found.</p>
            @endif
        @endif
    </div>


    {{-- Invoice related searching --}}
    @if ($invoices->isNotEmpty())
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
            {{-- <div>
            {{ $invoices->links('pagination::bootstrap-5') }}
        </div> --}}
        </div>
    @endif
@endsection
