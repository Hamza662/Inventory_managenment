@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header mb-0">
                Supplier Management
            </h5>
            {{-- <div class="input-group search" style="max-width: 300px;">
                <input type="text" name="search"  id="search" class="form-control" placeholder="Search supplier..." style="margin-right:15px">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i> <!-- Font Awesome search icon -->
                    </button>
                </div>
            </div> --}}
        </div>
        
        <div class="table-responsive text-nowrap">
            <table class="table" style="text-align:center">
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
                                <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="bx bx-edit-alt"></i> 
                                </a>
                                <form id="delete-form-{{ $supplier->id }}" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $supplier->id }});">
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
        {{$suppliers->links('pagination::bootstrap-5')}}
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script>
        $('#search').on('keyup',function(){
            var $value = $(this).val();

            console.log($value);
            if($value){
                $('.alldata').hide();
                $('.searchdata').show();
            }else{
                $('.alldata').show();
                $('.searchdata').hide();
            }
            $.ajax({
                type: 'get',
                url: '/search',
                data:{'search':$value},
                success:function(data){
                    console.log(data);
                    $('#content').html(data);
                }
            });
        });
    </script> --}}
@endsection
