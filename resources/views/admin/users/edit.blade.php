@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-3">
        <div class="card-header">
            <h5 class="mb-0">Edit user</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="user_name" value="{{ old('user_name', $user->user_name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">New password</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep">
                        @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Portal login type</label>
                        <select name="role" class="form-select" required>
                            @foreach (['admin', 'agent', 'user'] as $portalRole)
                                <option value="{{ $portalRole }}" @selected(old('role', $user->role) === $portalRole)>{{ ucfirst($portalRole) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Access role</label>
                        <select name="spatie_role" class="form-select">
                            <option value="">— None —</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('spatie_role', $selectedRole) === $role->name)>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" @selected(old('status', $user->status) === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">Update user</button>
                    <a href="{{ route('users.permissions', $user) }}" class="btn btn-outline-primary">
                        <i class="bx bx-key"></i> Permissions
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
