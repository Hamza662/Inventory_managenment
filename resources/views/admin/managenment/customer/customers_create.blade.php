@extends('admin.admin_dashboard')

@section('content')
    <div class="col-xl">
        <div class="card m-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create Customer</h5>
                <small class="text-muted float-end">Merged Customer</small>
            </div>
            <div class="card-body">
                <form id="customerForm" action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="basic-icon-default-fullname">Name</label><span class="text-danger">*</span>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="bx bx-user"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe"
                                    aria-describedby="basic-icon-default-fullname2" name="name">
                            </div>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="basic-icon-default-email">Email</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" id="basic-icon-default-email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="john@gmail.com"
                                    aria-label="john.doe" aria-describedby="basic-icon-default-email2" name="email">
                            </div>
                            <div class="form-text">You can use letters, numbers & periods</div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">


                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-address">Address</label><span class="text-danger">*</span>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-address2" class="input-group-text"><i
                                        class="bx bx-map"></i></span>
                                <input type="text" id="basic-icon-default-address"
                                    class="form-control @error('address') is-invalid @enderror" placeholder="123 Main St"
                                    aria-label="123 Main St" aria-describedby="basic-icon-default-address2" name="address">
                            </div>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-photo">Photo</label>
                            <div class="input-group">
                                <span id="basic-icon-default-photo2" class="input-group-text"><i
                                        class="bx bx-camera"></i></span>
                                <input type="file" id="basic-icon-default-photo"
                                    class="form-control @error('photo') is-invalid @enderror" aria-label="Upload Photo"
                                    aria-describedby="basic-icon-default-photo2" name="photo">
                            </div>
                            @error('photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#customerForm').on('submit', function(e) {
            e.preventDefault();

            let isValid = true;

            const name = $('#basic-icon-default-fullname');
            // const email = $('#basic-icon-default-email');
            const address = $('#basic-icon-default-address');

            if (name.val().trim() === '') {
                isValid = false;
                name.addClass('is-invalid');
            } else {
                name.removeClass('is-invalid');
            }

            // if (email.val().trim() === '') {
            //     isValid = false;
            //     email.addClass('is-invalid');
            // } else {
            //     email.removeClass('is-invalid');
            // }

            if (address.val().trim() === '') {
                isValid = false;
                address.addClass('is-invalid');
            } else {
                address.removeClass('is-invalid');
            }

            if (isValid) {
                this.submit();
            }
        });
    </script>
@endsection
