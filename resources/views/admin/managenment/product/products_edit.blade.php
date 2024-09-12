@extends('admin.admin_dashboard')

@section('content')
    <div class="col-xl">
        <div class="card m-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Product</h5>
                <small class="text-muted float-end">Merged Customer</small>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-fullname">Name</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="bx bx-user"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="basic-icon-default-fullname" placeholder="Product Name" aria-label="Product Name"
                                    aria-describedby="basic-icon-default-fullname2" name="name"
                                    value="{{ $product->name }}">
                            </div>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-fullname">Unit Price</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-number"><i
                                        class="bx bx-user"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="basic-icon-default-fullname" placeholder="Product Name" aria-label="Product Name"
                                    aria-describedby="basic-icon-default-fullname2" name="unit_price"
                                    value="{{ $product->unit_price }}">
                            </div>
                            @error('unit_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-supplier">Supplier</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-building"></i></span>
                                <select class="form-control @error('supplier_id') is-invalid @enderror"
                                    id="basic-icon-default-supplier" name="supplier_id">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
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
                            <label class="form-label" for="basic-icon-default-unit">Unit</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-unit2" class="input-group-text"><i
                                        class="bx bx-cube"></i></span>
                                <select class="form-control @error('unit_id') is-invalid @enderror"
                                    id="basic-icon-default-unit" name="unit_id">
                                    <option value="">Select Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ $product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('unit_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-category">Category</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-category2" class="input-group-text"><i
                                        class="bx bx-category"></i></span>
                                <select class="form-control @error('category_id') is-invalid @enderror"
                                    id="basic-icon-default-category" name="category_id">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>

            </div>
        </div>
    </div>
@endsection
