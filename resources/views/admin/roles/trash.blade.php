@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <h5 class="card-header">Trashed roles</h5>
        <div class="table-responsive text-nowrap">
            @if ($roles->isEmpty())
                @include('admin.partials.empty-state', [
                    'title' => 'Trash is empty',
                    'text' => 'Deleted roles will appear here.',
                ])
            @else
                <table class="table" style="text-align:center">
                    <thead class="table-light">
                        <tr>
                            <th>Sr</th>
                            <th>Original name</th>
                            <th>Deleted at</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $role->original_name ?: $role->name }}</td>
                                <td>{{ $role->deleted_at?->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('roles.restore', $role->id) }}" class="btn btn-success btn-sm">
                                        <i class="bx bx-reset"></i> Restore
                                    </a>
                                    <form id="delete-form-{{ $role->id }}" action="{{ route('roles.forcedelete', $role->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $role->id }});">
                                            <i class="fas fa-trash"></i> Delete permanently
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
