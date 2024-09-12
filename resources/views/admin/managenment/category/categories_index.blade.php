@extends('admin.admin_dashboard')
@section('content')
    <div class="card m-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header mb-0">
                Categories Management
            </h5>
            <div class="input-group search" style="max-width: 300px;">
                <input type="text" name="search" id="search" class="form-control" placeholder="Search category..."
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
    </div>
    <div>
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var value = $(this).val();
                if (value) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('categories.search') }}', // Make sure this route is correct
                        data: {
                            'search': value
                        },
                        success: function(data) {
                            $('.alldata').hide(); // Hide the main data
                            $('.searchdata').show(); // Show search result data
                            $('#content').html(data); // Inject the search results
                        },
                        error: function() {
                            console.error('Search request failed.');
                        }
                    });
                } else {
                    // Show original data if search field is empty
                    $('.alldata').show();
                    $('.searchdata').hide();
                }
            });
        });
    </script>
@endsection
