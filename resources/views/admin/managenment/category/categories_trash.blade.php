@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            All Trashed Customer
            <a href="{{ route('categories.index') }}" class="btn btn-primary btn-sm">Back To view</a>
        </h5>
        <div class="table-responsive text-nowrap">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Sr</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="{{ route('categories.restore', $category->id) }}"
                                            class="btn btn-success btn-sm hover-button">
                                            <i class="bx bx-edit-alt me-1"></i> Restore
                                        </a>

                                        {{-- <button type="button" class="btn btn-danger btn-sm hover-button delete-button"
                                            onclick="confirmDelete({{ $category->id }})">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button> --}}
                                        <form id="delete-form-{{ $category->id }}" action="{{ route('categories.forcedelete', $category->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $category->id }});">
                                                <i class="fas fa-trash"></i> Delete Permanently
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>



        </div>
    </div>
@endsection
