@extends('admin.admin_dashboard')

@section('content')
    <div class="col-xl">
        <div class="card m-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create Customer</h5>
                <small class="text-muted float-end">Merged Customer</small>
            </div>
            <div class="card-body">
                <form action="{{ route('customers.update', $customer->id) }}" id="profileUpdateForm" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-fullname">Name</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                    class="bx bx-user"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe"
                                aria-describedby="basic-icon-default-fullname2" name="name"
                                value="{{ $customer->name }}">
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
                                value="{{ $customer->email }}">
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
                        <label class="form-label" for="basic-icon-default-address">Address</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-address2" class="input-group-text"><i class="bx bx-map"></i></span>
                            <input type="text" id="basic-icon-default-address"
                                class="form-control @error('address') is-invalid @enderror" placeholder="123 Main St"
                                aria-label="123 Main St" aria-describedby="basic-icon-default-address2" name="address"
                                value="{{ $customer->address }}">
                        </div>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-photo">Photo</label>
                        <div class="input-group">
                            <span id="basic-icon-default-photo2" class="input-group-text"><i
                                    class="bx bx-camera"></i></span>
                            <input type="file" id="basic-icon-default-photo"
                                class="form-control @error('photo') is-invalid @enderror" aria-label="Upload Photo"
                                aria-describedby="basic-icon-default-photo2" name="photo" value="{{ $customer->photo }}">
                        </div>
                        @error('photo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="mt-3">
                            <label for="exampleInputPassword1" class="form-label"> </label>
                            <img class="w-px-40 h-auto rounded-circle"
                                src="{{ !empty($customer->photo) ? url('image/admin_images/' . $profiledata->photo) : url('image/blank_image.jpg') }}"
                                alt="profile" id="showImage">
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#profileUpdateForm').on('submit', function(event) {
                event.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success('User updated successfully!');
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    },
                    error: function(response) {
                        toastr.error('An error occurred while updating the profile.');
                    }
                });
            });
        });
    </script>
@endsection
