@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <h5 class="card-header">Trashed users</h5>
        <div class="table-responsive text-nowrap">
            @if ($users->isEmpty())
                @include('admin.partials.empty-state', [
                    'title' => 'Trash is empty',
                    'text' => 'Deleted users will appear here.',
                ])
            @else
                <table class="table" style="text-align:center">
                    <thead class="table-light">
                        <tr>
                            <th>Sr</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Deleted at</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->deleted_at?->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('users.restore', $user->id) }}" class="btn btn-success btn-sm">
                                        <i class="bx bx-reset"></i> Restore
                                    </a>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.forcedelete', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }});">
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
