@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <h5 class="card-header">
            Trashed suppliers
        </h5>
        <div class="table-responsive text-nowrap">
            @if ($suppliers->isEmpty())
                @include('admin.partials.empty-state', [
                    'title' => 'Trash is empty',
                    'text' => 'Deleted suppliers will appear here so you can restore or remove them forever.',
                ])
            @else
            <table class="table">
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
                <tbody class="table-border-bottom-0">
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->email }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->address }}</td>
                            <td>
                                <div class="dropdown">
                                    <a href="{{ route('suppliers.restore', $supplier->id) }}"
                                        class="btn btn-success btn-sm hover-button">
                                        <i class="bx bx-undo me-1"></i> 
                                    </a>

                                    <button type="button" class="btn btn-danger btn-sm hover-button delete-button"
                                        onclick="confirmDelete({{ $supplier->id }})">
                                        <i class="bx bx-trash me-1"></i> 
                                    </button>

                                    <form id="delete-form-{{ $supplier->id }}"
                                        action="{{ route('suppliers.forcedelete', $supplier->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
@endsection
