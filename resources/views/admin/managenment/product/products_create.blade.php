@extends('admin.admin_dashboard')
<style>
    .usman {
        padding: 10px 10px;
        border: 1px solid lightgray;
        border-radius: 3px;
    }
</style>
@section('content')
    <div class="col-xl">
        <div class="card m-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create Product</h5>
                <small class="text-muted float-end">Merged Customer</small>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" id="productForm">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-supplier">Supplier</label><span class="text-danger">*</span>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-building"></i></span>
                                <select class="form-control @error('supplier_id') is-invalid @enderror"
                                    id="basic-icon-default-supplier" name="supplier_id">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('supplier_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-category">Category</label><span class="text-danger">*</span>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-category2" class="input-group-text"><i
                                        class="bx bx-category"></i></span>
                                <select class="form-control @error('category_id') is-invalid @enderror"
                                    id="basic-icon-default-category" name="category_id">
                                    <option value="">Select Category</option>
                                    <!-- Categories will be populated dynamically -->
                                </select>
                            </div>
                            @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-unit">Unit</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-unit2" class="input-group-text"><i
                                        class="bx bx-cube"></i></span>
                                <select class="form-control @error('unit_id') is-invalid @enderror"
                                    id="basic-icon-default-unit" name="unit_id">
                                    <option value="">Select Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('unit_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-fullname">Name</label><span class="text-danger">*</span>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="bx bx-user"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="basic-icon-default-fullname" placeholder="Product Name" aria-label="Product Name"
                                    aria-describedby="basic-icon-default-fullname2" name="name">
                            </div>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-unit-price">Unit Price</label><span class="text-danger">*</span>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-unit-price2" class="input-group-text"><i
                                        class="bx bx-dollar"></i></span>
                                <input type="text" class="form-control @error('unit_price') is-invalid @enderror"
                                    id="basic-icon-default-unit-price" placeholder="Unit Price" aria-label="Unit Price"
                                    aria-describedby="basic-icon-default-unit-price2" name="unit_price">
                            </div>
                            @error('unit_price')
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
        document.addEventListener('DOMContentLoaded', function() {
            const supplierSelect = document.getElementById('basic-icon-default-supplier');
            const categorySelect = document.getElementById('basic-icon-default-category');
            const productSelect = document.getElementById('basic-icon-default-product');

            supplierSelect.addEventListener('change', function() {
                const supplierId = this.value;

                // Fetch categories
                fetch(`/getCategories/${supplierId}`)
                    .then(response => response.json())
                    .then(data => {
                        categorySelect.innerHTML = '<option value="">Select Category</option>';
                        data.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            categorySelect.appendChild(option);
                        });
                    });

                // Fetch products
                // fetch(`/getProducts/${supplierId}`)
                //     .then(response => response.json())
                //     .then(data => {
                //         productSelect.innerHTML = '<option value="">Select Product</option>';
                //         data.forEach(product => {
                //             const option = document.createElement('option');
                //             option.value = product.id;
                //             option.textContent = product.name;
                //             productSelect.appendChild(option);
                //         });
                //     });
            });
        });

        $(document).ready(function() {
            $('#productForm').on('submit', function(e) {
                e.preventDefault();

                let isValid = true;

                // Clear previous errors
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').empty();

                // Get form values
                const supplier = $('#basic-icon-default-supplier');
                const category = $('#basic-icon-default-category');
                // const unit = $('#basic-icon-default-unit');
                const name = $('#basic-icon-default-fullname');
                const unitPrice = $('#basic-icon-default-unit-price');

                // Validate Supplier
                if (supplier.val().trim() === '') {
                    isValid = false;
                    supplier.addClass('is-invalid');
                }

                // Validate Category
                if (category.val().trim() === '') {
                    isValid = false;
                    category.addClass('is-invalid');
                }

                // Validate Unit
                // if (unit.val().trim() === '') {
                //     isValid = false;
                //     unit.addClass('is-invalid');
                // }

                // Validate Name
                if (name.val().trim() === '') {
                    isValid = false;
                    name.addClass('is-invalid');
                }

                // Validate Unit Price
                if (unitPrice.val().trim() === '' || isNaN(unitPrice.val().trim())) {
                    isValid = false;
                    unitPrice.addClass('is-invalid');
                }

                // Submit the form if all fields are valid
                if (isValid) {
                    this.submit();
                }
            });
        });
    </script>
@endsection
