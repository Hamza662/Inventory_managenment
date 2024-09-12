<?php

namespace App\Http\Controllers\managenment;

use App\Models\Item;
use App\Models\Invoice;
use App\Models\customer;
use App\Models\BuyProduct;
use Illuminate\Http\Request;
use App\Models\CreditCustomer;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreditCustomerRequest;

class CreditCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all credit customers
        $creditCustomers = CreditCustomer::paginate(10);

        // Fetch all invoices with their related items
        $invoices = Invoice::with('items')->paginate(10);

        // Pass data to the view
        return view('admin.managenment.credit.creditcustomers_index', compact('creditCustomers', 'invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customerNames = customer::pluck('name', 'id');


        return view('admin.managenment.credit.creditcustomers_create', compact('customerNames'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreditCustomerRequest $request)
    {
        $creditcustomer = CreditCustomer::create([
            'customer_id' => $request->input('customer_id'),
            'invoice' => $request->input('invoice'),
            'date' => $request->input('date'),
            'due_amount' => $request->input('due_amount'),
        ]);
        return redirect()->route('creditcustomers.index')->with('success', 'Credit customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::with('items.product', 'customer', 'payments')->findOrFail($id);
        // Ensure payments is an empty collection if no payments exist
        $payments = $invoice->payments ?: collect();

        // Pass the invoice and items to the view
        return view('admin.managenment.credit.creditcustomers_show', compact('invoice', 'payments'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(CreditCustomer $creditcustomer)
    // {
    //      $creditCustomers = CreditCustomer::all();

    //     // Fetch all invoices with their related items
    //     $invoices = Invoice::with('items')->get();

    //     // Pass data to the view
    //     return view('admin.managenment.credit.creditcustomers_index', compact('creditCustomers', 'invoices'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreditCustomerRequest $request, CreditCustomer $creditcustomer)
    {
        $creditcustomer->update([
            'customer_id' => $request->input('customer_id'),
            'invoice' => $request->input('invoice'),
            'date' => $request->input('date'),
            'due_amount' => $request->input('due_amount'),
        ]);

        return redirect()->route('creditcustomers.index')->with('success', 'credit customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function ShowCreditCustomerPrintPage()
    {
        $creditcustomers = CreditCustomer::get();
        $customerName = customer::pluck('name');
        $GrandTotal = CreditCustomer::sum('due_amount');

        return view('admin.managenment.credit.grandtotal', compact('creditcustomers', 'customerName', 'GrandTotal'));
    }

    public function downloadPDF()
    {
        $creditcustomers = CreditCustomer::get();
        $customerName = customer::pluck('name');
        $grandtotal = CreditCustomer::sum('due_amount');

        $pdf = Pdf::loadview('admin.managenment.credit.pdf_page', compact('creditcustomers', 'customerName', 'grandtotal'));

        return $pdf->download('credit-customers.pdf');
    }
}
