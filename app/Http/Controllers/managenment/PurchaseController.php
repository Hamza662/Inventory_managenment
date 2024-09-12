<?php

namespace App\Http\Controllers\managenment;

use cache;
use App\Models\Buy;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\BuyProduct;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\BuyRequest;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    public function getCategories($supplierId)
    {
        // Assuming you have a relationship defined in Supplier
        $categories = Category::where('supplier_id', $supplierId)->get();
        return response()->json($categories);
    }

    public function getProducts($supplierId)
    {
        // Assuming you have a relationship defined in Supplier
        $products = Product::where('supplier_id', $supplierId)->get();
        return response()->json($products);
    }

    public function getProductsByCategory($supplierId, $categoryId)
    {
        $products = Product::where('supplier_id', $supplierId)
            ->where('category_id', $categoryId)
            ->get();

        return response()->json($products);
    }

    public function approve(Request $request)
    {
        $purchase = Buy::findOrFail($request->purchase_id);
        $purchase->sttaus = 'approved';
        $purchase->save();

        return response()->json(['success' => true]);
    }

    public function approvalPage()
    {

        $purchases = Buy::with(['buyProducts', 'supplier', 'category'])
            ->where('sttaus', 'pending')
            ->get();

        return view('admin.managenment..purchase.purchases_approve', compact('purchases'));
    }

    public function getUnitPrice($product_id)
    {

        $product = Product::find($product_id);

        return response()->json(['unit_price', $product->unit_price]);
    }


    // public function getProductDetails($productId)
    // {
    //     $product = Product::find($productId);
    //     if ($product) {
    //         return response()->json([
    //             'unit_price' => $product->unit_price
    //         ]);
    //     } else {
    //         return response()->json(['error' => 'Product not found'], 404);
    //     }   
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Buy::with(['supplier', 'buyProducts.product', 'buyProducts.category'])->paginate(10);

        return view('admin.managenment.purchase.purchases_index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        return view('admin.managenment.purchase.purchases_create', compact('suppliers', 'products'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(BuyRequest $request)
    {
        $data = $request->validated();

        // Create the Buy record
        $buy = Buy::create([
            'purchase_no' => $this->generateUniquePurchaseNo(),
            'date' => $data['date'],
            'supplier_id' => $data['supplier_id'],
            'category_id' => $data['category_id'],
        ]);

        // Loop through product rows and attach them to the Buy record
        foreach ($request->product_rows as $row) {
            $buy->products()->attach($row['product_id'], [
                'quantity' => $row['quantity'],
                'unit_price' => $row['unit_price'],
                'total_price' => $row['total_price'],
                'description' => $row['description'],
            ]);
        }

        return redirect()->route('purchases.approvalPage')->with('success', 'Purchase created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = Buy::findOrFail($id)->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase moved to trash successfully');
    }

    public function trash()
    {
        $purchases = Buy::onlyTrashed()->get();

        return view('admin.managenment.purchase.purchases_trash', compact('purchases'));
    }


    public function restore(string $id)
    {

        $purchase = Buy::withTrashed()->findOrFail($id);

        if (!is_null($purchase)) {
            $purchase->restore();
        }
        return redirect()->route('purchases.index')->with('success', 'purchase restore Successfully!!');
    }

    public function ForceDelete(string $id)
    {

        $purchase = Buy::withTrashed()->findOrFail($id);

        if (!is_null($purchase)) {
            $purchase->forceDelete();
        }
        return redirect()->route('purchases.trash')->with('success', 'product Permanent Delete Successfully!!');
    }

    public function generateReport(Request $request)
    {
        $purchases = [];
        if ($request->isMethod('post')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $purchases = Buy::whereBetween('date', [$startDate, $endDate])
                ->with('buyProducts.product')
                ->where('sttaus', 'approved')
                ->get();
        }

        return view('admin.managenment.purchase.purchases_daily', compact('purchases'));
    }

    public function generatePdf(Request $request)
    {
        // Validate the request to ensure valid dates are provided
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Get the start and end dates from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch purchases within the specified date range, including related products
        $purchases = Buy::whereBetween('date', [$startDate, $endDate])
            ->where('sttaus', 'approved')
            ->with('buyProducts.product')
            ->get();

        // Check if there are any purchases found, otherwise handle empty cases
        if ($purchases->isEmpty()) {
            return redirect()->back()->with('error', 'No purchases found for the selected criteria.');
        }

        // Load the view and pass the purchases data to it
        $pdf = Pdf::loadView('admin.managenment.purchase.report_pdf', compact('purchases'));

        // Generate a unique filename to avoid caching issues
        $fileName = 'purchase_report_' . time() . '.pdf';

        // Return the generated PDF as a download with the unique filename
        return $pdf->download($fileName);
    }




    protected function generateUniquePurchaseNo()
    {
        // Generate a unique purchase number, e.g., using a timestamp or other logic
        return 'PUR-' . time(); // Example format, modify as needed
    }

    public function getProductDetails($productId)
    {
        $product = Product::find($productId);

        if ($product) {
            return response()->json([
                'unit_price' => $product->unit_price
            ]);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    // public function search(Request $request)
    // {
    //     $query = $request->input('query');

    //     // Fetch records based on relationships: product name, supplier, or category
    //     $buyProducts = BuyProduct::whereHas('product', function ($q) use ($query) {
    //         $q->where('name', 'like', "%{$query}%");
    //     })
    //         ->orWhereHas('product.category', function ($q) use ($query) {
    //             $q->where('name', 'like', "%{$query}%");
    //         })
    //         ->orWhereHas('product.supplier', function ($q) use ($query) {
    //             $q->where('name', 'like', "%{$query}%");
    //         })
    //         ->with(['product.supplier', 'product.category'])
    //         ->get();

    //     return response()->json($buyProducts);
    // }




}
