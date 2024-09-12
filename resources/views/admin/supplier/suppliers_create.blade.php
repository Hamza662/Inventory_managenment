@extends('admin.admin_dashboard')

@section('content')
    <div class="col-xl">
        <div class="card m-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create Supplier</h5>
                <small class="text-muted float-end">Merged supplier</small>
            </div>
            <div class="card-body">
                <form id="supplierForm" action="{{ route('suppliers.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="fullname">Full Name</label><span class="text-danger">*</span>
                            <div class="input-group input-group-merge">
                                <span id="fullname-icon" class="input-group-text"><i class="bx bx-user"></i></span>
                                <input type="text" class="form-control" id="fullname" placeholder="John Doe"
                                    name="name">
                                <div class="invalid-feedback" id="fullnameError"></div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="email">Email</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="text" id="email" class="form-control" placeholder="john.doe"
                                    name="email">
                                <span id="email-icon" class="input-group-text">@example.com</span>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                            <div class="form-text">You can use letters, numbers & periods</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="phone">Phone No</label><span class="text-danger">*</span>
                            <div class="input-group input-group-merge">
                                <span id="phone-icon" class="input-group-text"><i class="bx bx-phone"></i></span>
                                <input type="text" id="phone" class="form-control" placeholder="658 799 8941"
                                    name="phone">
                                <div class="invalid-feedback" id="phoneError"></div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="address">Address</label>
                            <div class="input-group input-group-merge">
                                <span id="address-icon" class="input-group-text"><i class="bx bx-map"></i></span>
                                <input type="text" id="address" class="form-control" placeholder="123 Main St"
                                    name="address">
                                <div class="invalid-feedback" id="addressError"></div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="validateForm()">Create</button>
                </form>



            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function validateForm() {
            // Clear previous errors
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');

            // Get form values
            const name = $('#fullname').val().trim();
            // const email = $('#email').val().trim();
            const phone = $('#phone').val().trim();
            // const address = $('#address').val().trim();

            let valid = true;

            // Validate Full Name
            if (name === '') {
                valid = false;
                $('#fullname').addClass('is-invalid');
                $('#fullnameError').text('Full Name is required.');
            }

            // Validate Email
            // if (email === '') {
            //     valid = false;
            //     $('#email').addClass('is-invalid');
            //     $('#emailError').text('Email is required.');
            // } else if (!validateEmail(email)) {
            //     valid = false;
            //     $('#email').addClass('is-invalid');
            //     $('#emailError').text('Invalid email format.');
            // }

            // Validate Phone
            if (phone === '') {
                valid = false;
                $('#phone').addClass('is-invalid');
                $('#phoneError').text('Phone number is required.');
            } else if (!validatePhone(phone)) {
                valid = false;
                $('#phone').addClass('is-invalid');
                $('#phoneError').text('Invalid phone number format.');
            }

            // Validate Address
            // if (address === '') {
            //     valid = false;
            //     $('#address').addClass('is-invalid');
            //     $('#addressError').text('Address is required.');
            // }

            // If the form is valid, submit it
            if (valid) {
                $('#supplierForm').submit();
            }
        }

        // Email Validation
        // function validateEmail(email) {
        //     const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        //     return re.test(email);
        // }

        // Phone Validation
        function validatePhone(phone) {
            const re = /^[0-9]{10,14}$/; // Adjust the regex according to your phone format requirements
            return re.test(phone);
        }
    </script>
@endsection
