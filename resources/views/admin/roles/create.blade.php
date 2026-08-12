@extends('admin.admin_dashboard')

@section('content')
    <div class="card m-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Create role</h5>
            <small class="text-muted">Assign module permissions below</small>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Role name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-shield"></i></span>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="e.g. Warehouse Manager" required>
                        </div>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}"
                               class="form-control @error('description') is-invalid @enderror"
                               placeholder="Optional note">
                        @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h6 class="mb-3">Permissions</h6>
                @include('admin.partials.permission-tree', [
                    'groups' => $groups,
                    'selected' => old('permissions', []),
                ])

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create role</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
