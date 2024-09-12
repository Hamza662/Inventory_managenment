@extends('admin.admin_dashboard')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4" style="margin-top:25px">Supplier and Product Wise Report</h1>

        <div class="mb-3" style="text-align: center">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="reportType" id="supplierReport" value="supplier">
                <label class="form-check-label" for="supplierReport">Supplier Wise Report</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="reportType" id="productReport" value="product">
                <label class="form-check-label" for="productReport">Product Wise Report</label>
            </div>
        </div>

        <!-- Supplier Dropdown (Initially Hidden) -->
        <div class="row">
            <div class="mb-3 col-md-6" id="supplierDropdown" style="display:none;">
                <label for="supplier" class="form-label">Select Supplier</label>
                <select class="form-select" id="supplier" aria-label="Select Supplier">
                    <option value="">Choose a supplier</option>
                    <!-- Supplier options will be dynamically loaded here -->
                </select>
            </div>
        </div>
        <!-- Product Wise Report Filters (Initially Hidden) -->
        <div class="mb-3" id="productWiseFilters" style="display:none;">
            <div class="row">
                <div class="col-md-6">
                    <label for="category" class="form-label">Select Category</label>
                    <select class="form-select" id="category" aria-label="Select Category">
                        <option value="">Choose a category</option>
                        <!-- Category options will be dynamically loaded here -->
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="product" class="form-label">Select Product</label>
                    <select class="form-select" id="product" aria-label="Select Product">
                        <option value="">Choose a product</option>
                        <!-- Product options will be dynamically loaded here -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Report Table (Initially Hidden) -->
        <div id="reportTable" style="display:none;">
            <h3 class="text-center" id="supplierNameHeading"></h3>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Unit</th>
                        <th>Category</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Table data will be dynamically loaded here -->
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const supplierDropdown = $('#supplierDropdown');
            const supplierSelect = $('#supplier');
            const productWiseFilters = $('#productWiseFilters');
            const categorySelect = $('#category');
            const productSelect = $('#product');
            const reportTable = $('#reportTable');
            const tableBody = $('#tableBody');
            const supplierNameHeading = $('#supplierNameHeading');

            // Show/Hide the dropdowns and filters based on selected report type
            $('input[name="reportType"]').on('change', function() {
                if ($(this).val() === 'supplier') {
                    supplierDropdown.show();
                    productWiseFilters.hide();
                    reportTable.hide();
                    loadSuppliers();
                } else if ($(this).val() === 'product') {
                    supplierDropdown.hide();
                    productWiseFilters.show();
                    reportTable.hide();
                    loadCategories();
                }
            });

            // Load suppliers dynamically into the dropdown
            function loadSuppliers() {
                $.ajax({
                    url: '{{ route('invoices.getSupplier') }}',
                    type: 'GET',
                    success: function(data) {
                        supplierSelect.html('<option value="">Choose a supplier</option>');
                        $.each(data, function(index, supplier) {
                            supplierSelect.append($('<option>', {
                                value: supplier.id,
                                text: supplier.name
                            }));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading suppliers:', error);
                    }
                });
            }

            // Load categories dynamically into the dropdown
            function loadCategories() {
                $.ajax({
                    url: '{{ route('categorie.fetch') }}',
                    type: 'GET',
                    success: function(data) {
                        categorySelect.html('<option value="">Choose a category</option>');
                        $.each(data, function(index, category) {
                            categorySelect.append($('<option>', {
                                value: category.id,
                                text: category.name
                            }));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading categories:', error);
                    }
                });
            }

            // Load products based on the selected category
            categorySelect.on('change', function() {
                let categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        url: `/product/${categoryId}`,
                        type: 'GET',
                        success: function(data) {
                            productSelect.html('<option value="">Choose a product</option>');
                            if (data.length > 0) {
                                $.each(data, function(index, product) {
                                    productSelect.append($('<option>', {
                                        value: product.id,
                                        text: product.name
                                    }));
                                });
                            } else {
                                productSelect.html(
                                    '<option value="">No products available</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading products:', error);
                            productSelect.html(
                                '<option value="">Error loading products</option>');
                        }
                    });
                } else {
                    productSelect.html('<option value="">Choose a product</option>');
                }
            })

            // Load the report when a supplier is selected
            supplierSelect.on('change', function() {
                let supplierId = $(this).val();
                if (supplierId) {
                    $.ajax({
                        url: `/supplier/${supplierId}/report`,
                        type: 'GET',
                        success: function(data) {
                            supplierNameHeading.text(data.supplier_name);
                            tableBody.empty();
                            $.each(data.products, function(index, product) {
                                tableBody.append(`
                                    <tr>
                                        <td>${data.supplier_name}</td>
                                        <td>${product.unit}</td>
                                        <td>${product.category}</td>
                                        <td>${product.name}</td>
                                        <td>${product.quantity}</td>
                                    </tr>
                                `);
                            });
                            reportTable.show();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading report:', error);
                        }
                    });
                } else {
                    reportTable.hide();
                }
            });

            // Load the report when category and product are selected
            productSelect.on('change', function() {
                let productId = $(this).val();
                let categoryId = categorySelect.val();

                if (productId && categoryId) {
                    $.ajax({
                        url: `/category/${categoryId}/product/${productId}/report`,
                        type: 'GET',
                        success: function(data) {
                            tableBody.empty();
                            $.each(data, function(index, item) {
                                tableBody.append(`
                                    <tr>
                                        <td>${item.supplier_name}</td>
                                        <td>${item.unit}</td>
                                        <td>${item.category}</td>
                                        <td>${item.name}</td>
                                        <td>${item.remaining_quantity}</td>
                                    </tr>
                                `);
                            });
                            reportTable.show();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading report:', error);
                        }
                    });
                } else {
                    reportTable.hide();
                }
            });
        });
    </script>
@endsection
