@extends('admin.admin_dashboard')

@section('content')
    <div class="col-xl">
        <div class="card m-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create unit</h5>
                <small class="text-muted float-end">e.g. KG, Piece, Box</small>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('units.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label" for="basic-icon-default-fullname">Name</label>
                        <div class="input-group">
                            <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                    class="bx bx-ruler"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="basic-icon-default-fullname" placeholder="KG" aria-label="Unit name"
                                aria-describedby="basic-icon-default-fullname2" name="name" autocomplete="off">
                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection
