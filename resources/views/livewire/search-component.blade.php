{{-- <div>
    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="text"
            placeholder="Search by product name, supplier, category, or ID..."
            class="focus:ring-blue-500 w-full rounded-lg border px-4 py-2 focus:outline-none focus:ring-2" />
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table" style="text-align:center">
            <thead class="table-light">
                <tr>
                    <th>Sr</th>
                    <th>Name</th>
                    <th>Unit Price</th>
                    <th>Supplier Name</th>
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
                        <td>{{ $product->unit_price }}</td>
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
</div> --}}
