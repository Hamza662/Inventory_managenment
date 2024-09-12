@extends('admin.admin_dashboard')

@section('content')
    <style>
        .total {
            text-align: right;
            padding-right: 70px;
        }

        .delete-row {
            cursor: pointer;
            color: red;
            text-align: center;
        }
    </style>

    <div class="card m-4">
        <h5 class="card-header">Create Purchase</h5>
        <div class="card-body">
            <form id="purchase-form" action="{{ route('purchases.store') }}" method="POST">
                @csrf
                <div class="row">
                    {{-- <div class="mb-3 col-md-6">
                        <label for="purchase_no" class="form-label">Purchase No</label>
                        <input type="text" id="purchase_no" name="purchase_no" class="form-control" required>
                    </div> --}}

                    <div class="mb-3 col-md-6">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" id="purchase_date" name="date" class="form-control">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="supplier" class="form-label">Supplier</label><span class="text-danger">*</span>
                        <select id="supplier" name="supplier_id" class="form-select">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">

                    <div class="mb-3 col-md-6">
                        <label for="category" class="form-label">Category</label><span class="text-danger">*</span>
                        <select id="category" name="category_id" class="form-select" disabled>
                            <option value="">Select Category</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="product" class="form-label">Product</label><span class="text-danger">*</span>
                        <select id="product" name="product_id" class="form-select" disabled>
                            <option value="">Select Product</option>
                        </select>
                    </div>
                </div>

                <label for="unit_price" class="form-label"></label>
                <input type="hidden" id="unit_price" name="unit_price">

                <label for="quantity" class="form-label"></label>
                <input type="hidden" id="quantity" name="quantity">

                <button type="button" id="add-more" class="btn btn-secondary mb-3">Add More</button>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="product-rows">
                        <!-- Dynamic rows will be added here -->
                    </tbody>
                </table>

                <div id="total-price-display" class="mt-3 total">Total Price: 0</div>

                <button type="submit" class="btn btn-primary">Submit Purchase</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#supplier').change(function() {
                var supplierId = $(this).val();

                // Fetch categories
                $.get(`/suppliers/${supplierId}/categories`, function(data) {
                    $('#category').empty().append('<option value="">Select Category</option>');
                    $.each(data, function(index, category) {
                        $('#category').append('<option value="' + category.id + '">' +
                            category.name + '</option>');
                    });
                    $('#category').prop('disabled', false);
                });

                // Clear products when supplier changes
                $('#product').empty().append('<option value="">Select Product</option>').prop('disabled',
                    true);
            });

            $('#category').change(function() {
                var supplierId = $('#supplier').val();
                var categoryId = $(this).val();

                // Fetch products based on selected supplier and category
                $.get(`/suppliers/${supplierId}/categories/${categoryId}/products`, function(data) {
                    $('#product').empty().append('<option value="">Select Product</option>');
                    $.each(data, function(index, product) {
                        $('#product').append('<option value="' + product.id + '">' + product
                            .name + '</option>');
                    });
                    $('#product').prop('disabled', false);
                });
            });

            $('#product').change(function() {
                var productId = $(this).val(); // Get the selected product ID

                // Fetch unit price based on selected product
                $.get(`/products/${productId}/unit-price`, function(data) {
                    var unitPrice = data.unit_price;
                    console.log('Fetched Unit Price:', unitPrice); // Debugging line
                    $('#unit_price').val(unitPrice); // Set unit price in the hidden input field
                });
            });

            // Add More button functionality
            $('#add-more').click(function() {
                var categoryId = $('#category').val();
                var categoryName = $('#category option:selected').text();
                var productId = $('#product').val();
                var productName = $('#product option:selected').text();
                var unitPrice = $('#unit_price').val() || 0; // Default unit price
                console.log('Unit Price on Add More:', unitPrice); // Debugging line
                var index = $('#product-rows tr').length; // Get the current row count as index

                $('#product-rows').append(`
                <tr data-category-id="${categoryId}" data-product-id="${productId}">
                    <td>${categoryName}</td>
                    <td>${productName}</td>
                    <td><input type="number" class="form-control quantity" name="product_rows[${index}][quantity]" required></td><span class="text-danger">*</span>
                    <td><input type="text" class="form-control description" placeholder="Description" name="product_rows[${index}][description]"></td>
                    <td><input type="number" class="form-control unit-price" name="product_rows[${index}][unit_price]" value="${unitPrice}" required></td>
                    <td><input type="text" class="form-control total-price" name="product_rows[${index}][total_price]" readonly></td>
                    <td class="delete-row" onclick="deleteRow(this)">X</td>
                </tr>`);
                updateTotalPrice();
            });

            // Calculate total price
            $(document).on('input', '.quantity, .unit-price', function() {
                var row = $(this).closest('tr');
                var quantity = row.find('.quantity').val();
                var unitPrice = row.find('.unit-price').val();
                var totalPrice = quantity * unitPrice;

                row.find('.total-price').val(totalPrice);
                updateTotalPrice();
            });

            function updateTotalPrice() {
                let totalAmount = 0;

                $('#product-rows tr').each(function() {
                    totalAmount += parseFloat($(this).find('.total-price').val()) || 0;
                });

                $('#total-price-display').text('Total Price: ' + totalAmount);
            }

            // Function to delete a row
            window.deleteRow = function(element) {
                $(element).closest('tr').remove();
                updateTotalPrice();
            }

            // Handle form submission with AJAX
            $('#purchase-form').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serializeArray();

                $('#product-rows tr').each(function() {
                    var row = $(this);
                    formData.push({
                        name: 'product_rows[' + row.index() + '][product_id]',
                        value: row.data('product-id')
                    });
                    formData.push({
                        name: 'product_rows[' + row.index() + '][category_id]',
                        value: row.data('category-id')
                    });
                    formData.push({
                        name: 'product_rows[' + row.index() + '][quantity]',
                        value: row.find('.quantity').val()
                    });
                    formData.push({
                        name: 'product_rows[' + row.index() + '][unit_price]',
                        value: row.find('.unit-price').val()
                    });
                    formData.push({
                        name: 'product_rows[' + row.index() + '][total_price]',
                        value: row.find('.total-price').val()
                    });
                    formData.push({
                        name: 'product_rows[' + row.index() + '][description]',
                        value: row.find('.description').val()
                    });
                }); // Get the dynamically loaded fields


                // Send the form data to the server using AJAX
                $.ajax({
                    type: 'POST',
                    url: '{{ route('purchases.store') }}',
                    data: formData,
                    success: function(response) {
                        // Handle the response
                        console.log(response);

                        // Display Toastr success message
                        toastr.success('Purchase created successfully!', 'Success');

                        // Redirect to the index page after a delay to allow the user to see the message
                        setTimeout(function() {
                            window.location.href =
                                '{{ route('purchases.approvalPage') }}';
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        // Handle the error
                        console.error(xhr.responseText);
                        toastr.error('Failed to create purchase.', 'Error');
                    }
                });
            });
        });
    </script>
@endsection
