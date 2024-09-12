<style>
    .page-content {
        margin-top: 15px;
        margin-right: 20px;
        margin-left: 10px;
    }

    /* .left-wrapper{
        width: 25%;
        margin-left: 20px;
    } */
</style>
@extends('admin.admin_dashboard')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <div class="page-content">
        <div class="row profile-body">
            <!-- left wrapper start -->
            <div class="d-none d-md-block col-md-4 col-xl-4 left-wrapper">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">

                            <div>
                                <img class="w-px-40 h-auto rounded-circle"
                                    src="{{ !empty($profiledata->photo) ? url('image/admin_images/' . $profiledata->photo) : url('image/blank_image.jpg') }}"
                                    alt="profile">
                                <span class="h4 ms-3">{{ $profiledata->user_name }}</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Name:</label>
                            <p class="text-muted">{{ $profiledata->name }}</p>
                        </div>
                        <div class="mt-3">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Email:</label>
                            <p class="text-muted">{{ $profiledata->email }}</p>
                        </div>
                        <div class="mt-3">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">phone:</label>
                            <p class="text-muted">{{ $profiledata->phone }}</p>
                        </div>
                        <div class="mt-3">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Address:</label>
                            <p class="text-muted">{{ $profiledata->address }}</p>
                        </div>
                        <div class="mt-3 d-flex social-links">
                            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- left wrapper end -->
            <!-- middle wrapper start -->
            <div class="col-md-8 col-xl-8 middle-wrapper">
                <div class="row">
                    <div class="card">
                        <div class="card-body">

                            <h6 class="card-title">Update Admin Profile</h6>

                            <form class="forms-sample" method="POST" action="{{ route('admin.profile.update') }}"
                                enctype="multipart/form-data" id="profileUpdateForm">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="exampleInputUsername1" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off"
                                        value="{{ Auth::user()->name }}" name="name">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputUsername1" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off"
                                        value="{{ Auth::user()->user_name }}" name="user_name">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1"
                                        value="{{ $profiledata->email }}" name="email">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputUsername1" class="form-label">phone</label>
                                    <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off"
                                        value="{{ Auth::user()->phone }}" name="phone">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="exampleInputPassword1" autocomplete="off"
                                        value="{{ Auth::user()->address }}" name="address">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Photo</label>
                                    <input class="form-control" type="file" id="image" name="photo">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label"> </label>
                                    <img class="w-px-40 h-auto rounded-circle"
                                        src="{{ !empty($profiledata->photo) ? url('image/admin_images/' . $profiledata->photo) : url('image/blank_image.jpg') }}"
                                        alt="profile" id="showImage">
                                </div>

                                {{-- <div class="mb-3">
                                    <label for="role">Role</label>
                                    <select name="role" id="role" class="form-control">
                                        <option value="admin" {{ Auth::user()->role == 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                        <option value="user" {{ Auth::user()->role == 'user' ? 'selected' : '' }}>User
                                        </option>
                                        <option value="agent" {{ Auth::user()->role == 'agent' ? 'selected' : '' }}>Agent
                                        </option>
                                    </select>
                                </div> --}}
                                <button type="submit" class="btn btn-primary me-2" value="save">Save Changes</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- middle wrapper end -->
            <!-- right wrapper start -->

            <!-- right wrapper end -->
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
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
