@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            All Trashed Customer
            <a href="{{ route('customers.index') }}" class="btn btn-primary btn-sm">Back To view</a>
        </h5>
        <div class="table-responsive text-nowrap">
            <div class="table-responsive">
                <table class="table">
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
                    <tbody class="table-border-bottom-0">
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
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
                                    <div class="dropdown">
                                        <a href="{{ route('customers.restore', $customer->id) }}"
                                            class="btn btn-success btn-sm hover-button">
                                            <i class="bx bx-edit-alt me-1"></i> Restore
                                        </a>

                                        <button type="button" class="btn btn-danger btn-sm hover-button delete-button"
                                            onclick="confirmDelete({{ $customer->id }})">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button>

                                        <form id="delete-form-{{ $customer->id }}"
                                            action="{{ route('customers.forcedelete', $customer->id) }}" method="POST"
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
            </div>



        </div>
    </div>
@endsection
