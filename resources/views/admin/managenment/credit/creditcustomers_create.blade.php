@extends('admin.admin_dashboard')

@section('content')
    <div class="col-xl">
        <div class="card m-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create Credit Customer</h5>
                <small class="text-muted float-end">Merged Customer</small>
            </div>
            <div class="card-body">
                <form action="{{ route('creditcustomers.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer Name</label>
                        <select class="form-select select2" id="customer_id" name="customer_id">
                            <option selected disabled>Select Customer</option>
                            @foreach ($customerNames as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="invoice" class="form-label">Invoice No</label>
                        <input type="text" class="form-control" id="invoice" name="invoice">
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date">
                    </div>

                    <div class="mb-3">
                        <label for="due_amount" class="form-label">Due Amount</label>
                        <input type="text" class="form-control" id="due_amount" name="due_amount">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Select a customer",
                allowClear: true
            });
        });
    </script>
@endsection
