@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header mb-0">
                Customer Management
            </h5>
            <div class="input-group search" style="max-width: 300px;">
                <input type="text" name="search" id="search" class="form-control" placeholder="Search supplier..."
                    style="margin-right:15px">
                <div class="input-group-append">
                    {{-- <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i> <!-- Font Awesome search icon -->
                    </button> --}}
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <div class="table-responsive">
                <table class="table" style="text-align:center">
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
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary btn-sm">
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
    </div>
    <div>
        {{$customers->links('pagination::bootstrap-5')}}
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
