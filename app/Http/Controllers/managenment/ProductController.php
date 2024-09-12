<?php

namespace App\Http\Controllers\managenment;

use App\Models\unit;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->session()->get('query');

        $products = Product::with(['supplier', 'unit', 'category']);

        if ($query) {
            $products = $products->where('name', 'LIKE', "%$query%");
        }

        $products = $products->orderBy('created_at', 'ASC')->paginate(8);

        return view('admin.managenment.product.products_index', compact('products'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $units = unit::all();
        $categories = Category::all();

        return view('admin.managenment.product.products_create', compact('suppliers', 'units', 'categories'));
    }

    public function getCategories($supplier_id)
    {
        $categories = Category::where('supplier_id', $supplier_id)->get();

        return response()->json($categories);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_price' => 'required',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit_id' => 'required|exists:units,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        Product::create($request->only(['name', 'unit_price', 'supplier_id', 'unit_id', 'category_id']));

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
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
        $product = Product::findOrFail($id);
        $units = unit::all();
        $suppliers = Supplier::all();
        $categories = Category::all();

        return view('admin.managenment.product.products_edit', compact('product', 'units', 'suppliers', 'categories'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_price' => 'required',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit_id' => 'required|exists:units,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only(['name', 'unit_price', 'supplier_id', 'unit_id', 'category_id']));

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'product moved to trash successfully!');
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->get();

        return view('admin.managenment.product.products_trash', compact('products'));
    }

    public function restore(string $id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        if (!is_null($product)) {
            $product->restore();
        }
        return redirect()->route('products.index')->with('success', 'product restore Successfully!!');
    }

    public function ForceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        if (!is_null($product)) {
            $product->forceDelete();
        }
        return redirect()->back()->with('success', 'product Permanent Delete Successfully!!');
    }

    public function search(Request $request)
    {
        $output = '';
        $products = Product::where('name', 'like', '%' . $request->search . '%')->get();

        foreach ($products as $product) {
            $output .= "<tr>
                            <td>{$product->id}</td>
                            <td>{$product->name}</td>
                            <td>{$product->unit_price}</td>
                            <td>{$product->supplier->name}</td>
                            <td>{$product->unit->name}</td>
                            <td>" . ($product->category ? $product->category->name : 'N/A') . "</td>
                            <td>
                                <a href=\"" . route('products.edit', $product->id) . "\" class=\"btn btn-primary btn-sm\">
                                    <i class=\"bx bx-edit-alt\"></i>
                                </a>
                                <form id=\"delete-form-{$product->id}\" action=\"" . route('products.destroy', $product->id) . "\" method=\"POST\" style=\"display: inline;\">
                                    " . csrf_field() . "
                                    " . method_field('DELETE') . "
                                    <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"confirmDelete({$product->id});\">
                                        <i class=\"fas fa-trash\"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>";
        }

        return response($output);
    }

}
