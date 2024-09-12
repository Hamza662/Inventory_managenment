@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            Units Management
            {{-- <a href="{{ route('suppliers.trash') }}" class="btn btn-danger btn-sm">GoTo Trash</a> --}}
            {{-- <a href="{{ route('units.create') }}" class="btn btn-primary btn-sm">Create</a> --}}
        </h5>
        <div class="table-responsive text-nowrap">
            <table class="table" style="text-align:center">
                <thead class="table-light">
                    <tr>
                        <th>Sr</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($units as $unit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $unit->name }}</td>
                            <td>
                                <a href="{{ route('units.edit', $unit->id) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="bx bx-edit-alt"></i> 
                                </a>
                                <form id="delete-form-{{ $unit->id }}" action="{{ route('units.destroy', $unit->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $unit->id }});">
                                        <i class="fas fa-trash"></i> 
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
