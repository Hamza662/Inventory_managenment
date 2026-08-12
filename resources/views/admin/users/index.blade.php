@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-4">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            Users
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus"></i> Create user
            </a>
        </h5>
        <div class="table-responsive text-nowrap">
            @if ($users->isEmpty())
                @include('admin.partials.empty-state', [
                    'title' => 'No users found',
                    'text' => 'Add staff accounts and assign inventory permissions.',
                    'action' => route('users.create'),
                    'actionLabel' => 'Create user',
                ])
            @else
                <table class="table align-middle" style="text-align:center">
                    <thead class="table-light">
                        <tr>
                            <th>Sr</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Access role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse ($user->roles as $role)
                                        <span class="badge bg-label-primary">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-muted">—</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span class="badge {{ $user->status === 'active' ? 'bg-label-success' : 'bg-label-warning' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('users.permissions', $user) }}"
                                       class="btn btn-icon btn-sm btn-outline-primary"
                                       title="Assign permissions">
                                        <i class="bx bx-key"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm" title="Edit">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    @unless ($user->id === auth()->id() || $user->hasRole('Super Admin'))
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }});" title="Trash">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endunless
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
