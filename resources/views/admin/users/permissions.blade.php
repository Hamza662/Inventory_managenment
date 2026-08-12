@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-3">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1">Assign permissions</h5>
                <small class="text-muted">{{ $user->name }} · {{ $user->email }}</small>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">Back to users</a>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-primary border-0 mb-4">
                Tick permissions in the tree. Module group checkboxes select/unselect all children.
                Permissions marked <span class="badge bg-label-info">via role</span> already come from the user's access role — you can still grant them directly.
            </div>

            <form action="{{ route('users.permissions.sync', $user) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.partials.permission-tree', [
                    'groups' => $groups,
                    'selected' => old('permissions', $selected),
                    'viaRoles' => $viaRoles,
                ])
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check"></i> Save permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
