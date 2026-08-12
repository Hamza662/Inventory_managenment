@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            Roles
            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus"></i> Create role
            </a>
        </h5>
        <div class="table-responsive text-nowrap">
            @if ($roles->isEmpty())
                @include('admin.partials.empty-state', [
                    'title' => 'No roles found',
                    'text' => 'Create roles like Admin or Agent to control inventory access.',
                    'action' => route('roles.create'),
                    'actionLabel' => 'Create role',
                ])
            @else
                <table class="table" style="text-align:center">
                    <thead class="table-light">
                        <tr>
                            <th>Sr</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Permissions</th>
                            <th>Users</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $role->name }}</td>
                                <td>{{ $role->description ?: '—' }}</td>
                                <td><span class="badge bg-label-primary">{{ $role->permissions_count }}</span></td>
                                <td><span class="badge bg-label-info">{{ $role->users_count }}</span></td>
                                <td>
                                    @if ($role->name !== 'Super Admin')
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary btn-sm">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <form id="delete-form-{{ $role->id }}" action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $role->id }});">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-label-secondary">Protected</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
