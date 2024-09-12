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
        <h5 class="card-header">Create Invoice</h5>
        <div class="card-body">
            <form id="invoice-form" action="{{ route('invoices.store') }}" method="POST">
                <!-- Include CSRF token for Laravel -->
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" id="purchase_date" name="date" class="form-control">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="supplier" class="form-label">Supplier</label>
                        <select id="supplier_id" name="supplier_id" class="form-select">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="category" class="form-label">Category</label>
                        <select id="category" name="category_id" class="form-select">
                            <option value="">Select Category</option>
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="product" class="form-label">Product</label>
                        <select id="product" name="product_id" class="form-select">
                            <option value="">Select Product</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" readonly>
                    </div>
                </div>
                <label for="unit_price" class="form-label"></label>
                <input type="hidden" id="unit_price" name="unit_price">
                <button type="button" id="add-more" class="btn btn-secondary mb-3">Add More</button>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Unit Price</th>
                            <th>Discount (%)</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="product-rows">
                        <!-- Dynamic rows will be added here -->
                    </tbody>
                </table>

                <div id="total-price-display" class="mt-3 total">Total Price: 0</div>
                <input type="hidden" id="total_price" name="total_price" value="0">
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select id="customer_id" name="customer_id" class="form-select">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select id="payment_status" name="payment_status" class="form-select">
                            <option value="full_paid">Full Paid</option>
                            <option value="full_due">Full Due</option>
                            <option value="partial_paid">Partial Paid</option>
                        </select>
                    </div>

                    <div id="partial_amount_div" class="mb-3 hidden col-md-4">
                        <label for="partial_amount" class="form-label">Partial Amount</label>
                        <input type="number" id="partial_amount" name="partial_amount" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit Invoice</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle partial amount field visibility
            $('#payment_status').change(function() {
                if ($(this).val() === 'partial_paid') {
                    $('#partial_amount_div').removeClass('hidden');
                } else {
                    $('#partial_amount_div').addClass('hidden');
                    $('#partial_amount').val(''); // Clear partial amount when not in partial_paid status
                }
            });

            // Fetch categories based on selected supplier
            $('#supplier_id').change(function() {
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
                $('#quantity').val('').prop('disabled', true);
            });

            // Fetch products based on selected category
            $('#category').change(function() {
                var supplierId = $('#supplier_id').val();
                var categoryId = $(this).val();

                // Fetch products
                $.get(`/suppliers/${supplierId}/categories/${categoryId}/products`, function(data) {
                    $('#product').empty().append('<option value="">Select Product</option>');
                    $.each(data, function(index, product) {
                        $('#product').append('<option value="' + product.id + '">' + product
                            .name + '</option>');
                    });
                    $('#product').prop('disabled', false);
                });
            });

            // Fetch unit price based on selected product
            $('#product').change(function() {
                var productId = $(this).val();

                $.get(`/products/${productId}/details`, function(data) {
                    if (data.error) {
                        console.error(data.error);
                    } else {
                        $('#unit_price').val(data.unit_price || 0); // Set unit price
                        $('#quantity').val(data.quantity || 0); // Set quantity if needed
                    }
                });
            });

            // Add More button functionality
            $('#add-more').click(function() {
                var categoryId = $('#category').val();
                var categoryName = $('#category option:selected').text();
                var productId = $('#product').val();
                var productName = $('#product option:selected').text();
                var unitPrice = $('#unit_price').val() || 0;

                $('#product-rows').append(`
                    <tr>
                        <td><input type="hidden" name="items[][category_id]" value="${categoryId}">${categoryName}</td>
                        <td><input type="hidden" name="items[][product_id]" value="${productId}">${productName}</td>
                        <td><input type="number" class="form-control quantity" name="items[][quantity]" required></td>
                        <td><input type="text" class="form-control description" name="items[][description]"></td>
                        <td><input type="number" class="form-control price" name="items[][price]" value="${unitPrice}" required></td>
                        <td><input type="number" class="form-control discount" name="items[][discount]"></td>
                        <td><input type="text" class="form-control total-price" name="items[][total_price]" readonly></td>
                        <td class="delete-row" onclick="deleteRow(this)">X</td>
                    </tr>
                `);
                updateTotalPrice();
            });

            // Calculate total price for each row and update grand total
            $(document).on('input', '.quantity, .price, .discount', function() {
                var row = $(this).closest('tr');
                var quantity = parseFloat(row.find('.quantity').val()) || 0;
                var price = parseFloat(row.find('.price').val()) || 0;
                var discount = parseFloat(row.find('.discount').val()) || 0;

                var totalPrice = (quantity * price) - (quantity * price * discount / 100);
                row.find('.total-price').val(totalPrice.toFixed(2)); // Update total price for the row
                updateTotalPrice();
            });

            function updateTotalPrice() {
                let totalAmount = 0;

                $('#product-rows tr').each(function() {
                    totalAmount += parseFloat($(this).find('.total-price').val()) || 0;
                });

                $('#total-price-display').text('Total Price: ' + totalAmount.toFixed(2));
                $('#total_price').val(totalAmount.toFixed(2)); // Update hidden field
            }

            // Function to delete a row
            window.deleteRow = function(element) {
                $(element).closest('tr').remove();
                updateTotalPrice();
            };

            // Handle form submission with AJAX
            $('#invoice-form').submit(function(event) {
                event.preventDefault();

                var items = [];
                $('#product-rows tr').each(function(index, rowElement) {
                    var row = $(rowElement);
                    items.push({
                        category_id: row.find('input[name="items[][category_id]"]').val(),
                        product_id: row.find('input[name="items[][product_id]"]').val(),
                        quantity: row.find('.quantity').val(),
                        description: row.find('.description').val(),
                        price: row.find('.price').val(),
                        discount: row.find('.discount').val(),
                        total_price: row.find('.total-price').val(),
                    });
                });

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        date: $('#purchase_date').val(),
                        customer_id: $('#customer_id').val(),
                        payment_status: $('#payment_status').val(),
                        supplier_id: $('#supplier_id').val(),
                        total_price: $('#total_price').val(),
                        partial_amount: $('#partial_amount').val(),
                        items: items,
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Invoice created successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#invoice-form')[0].reset(); // Reset the form fields
                                $('#product-rows').empty(); // Clear dynamic rows
                                $('#total_price').val(
                                    '0'); // Reset total price hidden field
                                window.location.href =
                                    '{{ route('invoices.approvalPage') }}'; // Redirect to the approval page
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while creating the invoice.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            var errorMessages = [];
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errorMessages.push(errors[key].join('\n'));
                                }
                            }
                            alert('Errors:\n' + errorMessages.join('\n'));
                        } else {
                            alert('An error occurred while creating the invoice.');
                        }
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
