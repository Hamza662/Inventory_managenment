@extends('admin.admin_dashboard')

@section('content')
    <div class="col-xl">
        <div class="card m-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create Category</h5>
                <small class="text-muted float-end">Merged Category</small>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST" id="categoryForm">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-fullname">Name</label><span class="text-danger">*</span>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="basic-icon-default-fullname" placeholder="Electronics" aria-label="John Doe"
                                    aria-describedby="basic-icon-default-fullname2" name="name">
                            </div>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="supplier" class="form-label">Supplier</label><span class="text-danger">*</span>
                            <select name="supplier_id" id="supplier" class="form-select">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();

                let isValid = true;

                const name = $('#basic-icon-default-fullname');
                const supplier = $('#supplier');

                // Validate Name
                if (name.val().trim() === '') {
                    isValid = false;
                    name.addClass('is-invalid');
                } else {
                    name.removeClass('is-invalid');
                }

                // Validate Supplier
                if (supplier.val().trim() === '') {
                    isValid = false;
                    supplier.addClass('is-invalid');
                } else {
                    supplier.removeClass('is-invalid');
                }

                // Submit the form if all fields are valid
                if (isValid) {
                    this.submit();
                }
            });
        });
    </script>
@endsection
