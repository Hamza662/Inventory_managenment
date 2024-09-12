@extends('admin.admin_dashboard')

@section('content')
    <div class="col-xl">
        <div class="card m-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Unit</h5>
                <small class="text-muted float-end">Update Unit</small>
            </div>
            <div class="card-body">
                <form action="{{ route('units.update', $unit->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-fullname">Name</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                    class="bx bx-user"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe"
                                aria-describedby="basic-icon-default-fullname2" name="name" value="{{ old('name', $unit->name) }}">
                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
