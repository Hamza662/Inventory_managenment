@extends('admin.admin_dashboard')

@section('content')
<div class="container mt-5">
    <h1>Credit Customers</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Name</th>
                <th>Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($creditcustomers as $creditcustomer)
                <tr>
                    <td>{{ $creditcustomer->id }}</td>
                    <td>{{ $creditcustomer->invoice }}</td>
                    <td>{{ $creditcustomer->date }}</td>
                    <td>{{ $creditcustomer->customer->name }}</td>
                    <td>${{ $creditcustomer->due_amount }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"><strong>Grand Due Amount</strong></td>
                <td><strong>${{ $GrandTotal }}</strong></td>
            </tr>
        </tbody>
    </table>
    <a href="{{ route('download_pdf') }}" class="btn btn-primary">Download PDF <i class="fa-regular fa-file-pdf"></i></a>
</div>
@endsection
