<?php

namespace App\Http\Controllers\managenment;

use App\Models\Item;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
use App\Models\customer;
use App\Models\Supplier;
use App\Models\BuyProduct;
use Illuminate\Http\Request;
use App\Models\CreditCustomer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use Illuminate\Support\Facades\Redirect;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all invoices with their associated customers and products
        $invoices = Invoice::with(['customer', 'items.product'])
            ->orderBy('created_at', 'ASC')
            ->paginate(5);


        // Return the view with the retrieved invoices
        return view('admin.managenment.invoice.invoices_index', compact('invoices'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::get();
        $customers = customer::get();
        return view('admin.managenment.invoice.invoices_create', compact('suppliers', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'payment_status' => 'required|in:full_paid,full_due,partial_paid',
            'supplier_id' => 'required|exists:suppliers,id',
            'total_price' => 'required|numeric',
            'partial_amount' => 'nullable|numeric',
            'items' => 'required|array',
            'items.*.category_id' => 'required|exists:categories,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric',
            'items.*.description' => 'nullable|string',
            'items.*.price' => 'required|numeric',
            'items.*.discount' => 'required|numeric',
            'items.*.total_price' => 'required|numeric',
        ]);

        // Create the invoice
        $invoice = Invoice::create([
            'date' => $validated['date'],
            'customer_id' => $validated['customer_id'],
            'payment_status' => $validated['payment_status'],
            'supplier_id' => $validated['supplier_id'],
            'total_price' => $validated['total_price'],
            'partial_amount' => $validated['partial_amount']
        ]);

        // Create the items
        foreach ($validated['items'] as $itemData) {
            $invoice->items()->create($itemData);
        }


        return response()->json(['success' => true]);
    }
    public function checkStock(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Fetch product stock from the database
        $product = Product::find($productId);

        if (!$product || $product->stock < $quantity) {
            return response()->json(['error' => 'Insufficient stock for product: ' . $product->name], 400);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the invoice by its ID
        $invoice = Invoice::with('items.product', 'customer', 'supplier')->findOrFail($id);

        // Check if the invoice exists
        if (!$invoice) {
            return redirect()->route('invoices.index')->with('error', 'Invoice not found.');
        }

        // Pass the invoice data to the view
        return view('admin.managenment.invoice.invoices_show', compact('invoice'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Retrieve the Invoice by its ID
        $invoice = Invoice::findOrFail($id);

        // Retrieve the related invoice items based on the invoice ID
        $invoiceItems = Item::where('invoice_id', $invoice->id)->get();

        // Retrieve the list of customer names for the select dropdown
        $customerName = Customer::pluck('name', 'id');

        $invoices = Invoice::with('items.category')->get();

        // Pass the invoice, invoiceItems, and customerName to the view
        return view('admin.managenment.credit.creditcustomers_edit', compact('invoice', 'invoiceItems', 'customerName', 'invoices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request
        $request->validate([
            'payment_status' => 'required|string',
            'partial_amount' => 'nullable|numeric',
            'date' => 'required|date',
        ]);

        // Find the invoice by ID
        $invoice = Invoice::findOrFail($id);

        // Calculate the new partial amount
        if ($request->input('payment_status') === 'partial_paid') {
            $newPartialAmount = $request->input('partial_amount');
            $invoice->partial_amount += $newPartialAmount;
        } else {
            $invoice->partial_amount = 0; // Reset to 0 if not partial payment
        }

        // Calculate the total price
        $totalPrice = $invoice->items->sum('total_price');
        $invoice->total_price = $totalPrice;

        // Calculate due amount
        $dueAmount = $invoice->total_price - $invoice->partial_amount;

        // Update the invoice's due amount and other fields
        $invoice->payment_status = $request->input('payment_status');
        $invoice->date = $request->input('date');

        // Save the updated invoice
        $invoice->save();

        // Redirect back with a success message
        return redirect()->route('creditcustomers.index', $invoice->id)->with('success', 'Invoice updated successfully.');
    }

    public function destroy(string $id){
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success' , 'Invoice goto trashed successfully');
    }
    public function getCategoriesBySupplier($supplierId)
    {
        // Fetch categories based on the supplier ID
        // Assuming you have a relationship between Supplier and Category models
        $categories = Category::where('supplier_id', $supplierId)->get();

        // Return JSON response
        return response()->json($categories);
    }

    public function getProductsByCategory($supplierId, $categoryId)
    {
        // Fetch products based on the supplier ID and category ID
        // Assuming you have a relationship between Product and Category models
        $products = Product::where('supplier_id', $supplierId)
            ->where('category_id', $categoryId)
            ->get();

        // Return JSON response
        return response()->json($products);
    }


    public function getProductDetails($productId)
    {
        $product = Product::find($productId);
        $buyProduct = BuyProduct::where('product_id', $productId)->first();
        // $buyProduct = BuyProduct::where('product_id', $productId)
        //                  ->groupBy('product_id')
        //                  ->selectRaw('product_id, SUM(quantity) as total_quantity'); // example of aggregation
                         


        if ($product && $buyProduct) {
            return response()->json([
                'unit_price' => $product->unit_price,
                'quantity' => $buyProduct->quantity
            ]);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function approvalPage()
    {
        $invoices = Invoice::with(['supplier'])
            ->orderBy('created_at', 'ASC')
            ->paginate(5);
        return view('admin.managenment.invoice.invoices_approve', compact('invoices'));
    }

    public function approve(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $outOfStockItems = [];

        // Check if the items have enough stock
        foreach ($invoice->items as $item) {
            $currentStock = BuyProduct::where('product_id', $item->product_id)->sum('quantity');
            if ($item->quantity > $currentStock) {
                $outOfStockItems[] = $item->product->name;
            }
        }

        if (!empty($outOfStockItems)) {
            $message = 'The following products are out of stock: ' . implode(', ', $outOfStockItems);
            return response()->json(['success' => false, 'message' => $message]);
        }

        // Mark invoice as approved
        $invoice->status = 'approved';
        $invoice->save();

        return response()->json(['success' => true]);
    }

    public function ShowInvoicePrintPage($id)
    {
        // Find the invoice by its ID
        $invoice = Invoice::with('items.product.category', 'customer', 'supplier')->findOrFail($id);

        // Check if the invoice exists
        if (!$invoice) {
            return redirect()->route('invoices.index')->with('error', 'Invoice not found.');
        }

        // Pass the invoice data to the view
        return view('admin.managenment.invoice.invoices_print', compact('invoice'));
    }


    public function trash()
    {
        $invoices = Invoice::onlyTrashed()->get();

        return view('admin.managenment.invoice.invoices_trash', compact('invoices'));
    }

    public function restore(string $id)
    {

        $invoice = Invoice::withTrashed()->findOrFail($id);

        if (!is_null($invoice)) {
            $invoice->restore();
        }
        return redirect()->route('invoices.index')->with('success', 'invoice restore Successfully!!');
    }

    public function ForceDelete(string $id)
    {

        $invoice = Invoice::withTrashed()->findOrFail($id);

        if (!is_null($invoice)) {
            $invoice->forceDelete();
        }
        return redirect()->route('invoices.trash')->with('success', 'invoice Permanent Delete Successfully!!');
    }

    // public function downloadPDF(Invoice $invoice)
    // {

    //     $invoice = $invoice->load('items.product', 'customer', 'supplier');
    
        
    //     $pdf = Pdf::loadView('admin.managenment.invoice.pdf_generate', compact('invoice'));
    
    //     return $pdf->download('customer_invoice.pdf');
    // }
    

    public function generateInvoiceReport(Request $request)
    {
        $invoices = [];

        if ($request->isMethod('post')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $invoices = Invoice::whereBetween('date', [$startDate, $endDate])
                ->with('customer', 'supplier', 'items.product')
                ->where('status', 'approved')
                ->get();
        }

        return view('admin.managenment.invoice.invoices_daily', compact('invoices'));
    }

    public function generatePdf(Request $request)
    {
        // Retrieve dates from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch invoices based on dates
        $invoices = Invoice::whereBetween('date', [$startDate, $endDate])->get();

        // Generate PDF
        $pdf = Pdf::loadView('admin.managenment.invoice.invoice_report_pdf', [
            'invoices' => $invoices,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download('invoice_report.pdf');
    }

    public function StockReport()
    {
        $products = Product::whereHas('items', function ($query) {
            $query->whereHas('invoice', function ($query) {
                $query->where('status', 'approved');
            });
        })
            ->with([
                'category',
                'unit',
                'supplier',
                'items' => function ($query) {
                    $query->select('product_id', DB::raw('SUM(quantity) as total_sold'))
                        ->groupBy('product_id');
                }
            ])
            ->get();

        return view('admin.managenment.invoice.invoices_stock', compact('products'));
    }




    public function GetSupplier()
    {
        // Fetch all suppliers
        $suppliers = Supplier::all();
        return response()->json($suppliers);

    }



    public function getSupplierReport($supplierId)
    {
        // Fetch the supplier
        $supplier = Supplier::findOrFail($supplierId);

        // Fetch products related to the supplier and their quantities
        $products = Product::where('supplier_id', $supplierId)
            ->with([
                'items' => function ($query) {
                    $query->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                        ->groupBy('product_id');
                }
            ])
            ->get();

        // Map the product data including unit, category, and total quantity
        $data = $products->map(function ($product) use ($supplier) {
            return [
                'supplier_name' => $supplier->name,
                'unit' => $product->unit->name, // Make sure this attribute exists
                'category' => $product->category->name, // Make sure this attribute exists
                'name' => $product->name,
                'quantity' => $product->items->first()->total_quantity ?? 0,
            ];
        });

        // Return the data as JSON response
        return response()->json([
            'supplier_name' => $supplier->name,
            'products' => $data
        ]);
    }




    public function getProductReport($categoryId, $productId)
    {
        $products = Product::where('category_id', $categoryId)
            ->where('id', $productId)
            ->with([
                'items' => function ($query) {
                    $query->whereHas('invoice', function ($q) {
                        $q->where('status', 'approved');
                    })
                        ->select('product_id', DB::raw('SUM(quantity) as remaining_quantity'))
                        ->groupBy('product_id');
                }
            ])
            ->get();

        $data = $products->map(function ($product) {
            return [
                'supplier_name' => $product->supplier->name,
                'unit' => $product->unit->name,
                'category' => $product->category->name,
                'name' => $product->name,
                'remaining_quantity' => $product->items->sum('remaining_quantity'),
            ];
        });

        return response()->json($data);
    }


    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function getProductsWithCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)
            ->get();

        if ($products->isEmpty()) {
            return response()->json(['error' => 'No products available'], 404);
        }

        $data = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
            ];
        });

        return response()->json($data);
    }











    //  public function getProductUnitPrice($productId)
    // {
    //     $product = Product::find($productId);

    //     if ($product) {
    //          return response()->json([
    //              'unit_price' => $product->unit_price,
    //              'quantity' => $product->buyProduct->quantity // Assuming the relationship is set correctly
    //          ]);
    //     } else {
    //          return response()->json(['error' => 'Product not found'], 404);
    //     }
    // }

}
