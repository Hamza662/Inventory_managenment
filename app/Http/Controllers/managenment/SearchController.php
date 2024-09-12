<?php

namespace App\Http\Controllers\managenment;

use App\Models\Buy;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
use App\Models\customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function redirectSearch(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:255',
        ]);
        $query = $request->input('query');

        // Search in all relevant tables
        $customers = Customer::where('name', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->get();
        $suppliers = Supplier::where('name', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->orWhere('phone', 'LIKE', "%$query%")
            ->get();

        $products = Product::where('name', 'LIKE', "%$query%")->get();

        $categories = Category::where('name', 'LIKE', "%$query%")->get();

        $purchases = Buy::with(['supplier', 'buyProducts.product.category'])
            ->whereHas('supplier', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->orWhereHas('buyProducts.product', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->orWhereHas('buyProducts.product.category', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->paginate(10);


        $invoices = Invoice::with(['customer', 'items.product'])
                    ->whereHas('customer', function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%$query%");
                    })->get();

        // Apply search filtering if a query is provided
        // if ($query) {
        //     $invoices->where(function ($q) use ($query) {
        //         $q->whereHas('customer', function ($q) use ($query) {
        //             $q->where('name', 'LIKE', "%$query%");
        //         })
        //             ->orWhereHas('items.product', function ($q) use ($query) {
        //                 $q->where('name', 'LIKE', "%$query%");
        //             });
        //     });
        // }
        // Paginate the results
        // $invoices = $invoicesQuery->paginate(5);
        // Check if there are results in any of the tables
        if ($customers->isEmpty() && $suppliers->isEmpty() && $products->isEmpty() && $categories->isEmpty() && $purchases->isEmpty()) {
            return back()->with('error', 'No results found');
        }

        // Return the view with customers, suppliers, products, and categories
        return view('admin.search_results', compact('customers', 'suppliers', 'products', 'categories', 'purchases', 'invoices'));
    }


}
