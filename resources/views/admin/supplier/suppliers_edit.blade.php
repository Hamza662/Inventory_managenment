@extends('admin.admin_dashboard')

@section('content')
    <div class="col-xl">
        <div class="card m-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Supplier</h5>
                <small class="text-muted float-end">Update supplier details</small>
            </div>
            <div class="card-body">
                <form action="{{ route('suppliers.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Use method('PUT') for update --}}
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-fullname">Name</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                    class="bx bx-user"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe"
                                aria-describedby="basic-icon-default-fullname2" name="name" value="{{ $user->name }}">
                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-email">Email</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                            <input type="text" id="basic-icon-default-email"
                                class="form-control @error('email') is-invalid @enderror" placeholder="john.doe"
                                aria-label="john.doe" aria-describedby="basic-icon-default-email2" name="email"
                                value="{{ $user->email }}">
                            <span id="basic-icon-default-email2" class="input-group-text">@example.com</span>
                        </div>
                        <div class="form-text">You can use letters, numbers & periods</div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-phone">Phone No</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-phone2" class="input-group-text"><i class="bx bx-phone"></i></span>
                            <input type="text" id="basic-icon-default-phone"
                                class="form-control @error('phone') is-invalid @enderror" placeholder="658 799 8941"
                                aria-label="658 799 8941" aria-describedby="basic-icon-default-phone2" name="phone"
                                value="{{ $user->phone }}">
                        </div>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-address">Address</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-address2" class="input-group-text"><i class="bx bx-map"></i></span>
                            <input type="text" id="basic-icon-default-address"
                                class="form-control @error('address') is-invalid @enderror" placeholder="123 Main St"
                                aria-label="123 Main St" aria-describedby="basic-icon-default-address2" name="address"
                                value="{{ $user->address }}">
                        </div>
                        @error('address')
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
