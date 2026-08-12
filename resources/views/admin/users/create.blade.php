@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-3">
        <div class="card-header">
            <h5 class="mb-0">Create user</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="user_name" value="{{ old('user_name') }}" class="form-control @error('user_name') is-invalid @enderror" required>
                        @error('user_name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Portal login type</label>
                        <select name="role" class="form-select" required>
                            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                            <option value="agent" @selected(old('role') === 'agent')>Agent</option>
                            <option value="user" @selected(old('role') === 'user')>User</option>
                        </select>
                        <small class="text-muted">Controls admin vs agent dashboard access</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Access role</label>
                        <select name="spatie_role" class="form-select">
                            <option value="">— None —</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('spatie_role') === $role->name)>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create user</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
