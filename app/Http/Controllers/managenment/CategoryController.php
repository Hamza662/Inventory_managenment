<?php

namespace App\Http\Controllers\managenment;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->session()->get('query');

        $categories = Category::with('supplier');

        if ($query) {
            $categories = $categories->where('name', 'LIKE', "%$query%");
        }

        $categories = $categories->paginate(5);

        return view('admin.managenment.category.categories_index', compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('admin.managenment.category.categories_create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $unit = $request->validate([
            'name' => 'required',
            'supplier_id' => 'required'
        ]);

        Category::create($unit);

        return redirect()->route('categories.index')->with('success', 'category created successfully!');
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
        $category = Category::findOrFail($id);
        $suppliers = Supplier::all();
        return view('admin.managenment.category.categories_edit', compact('category', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update the unit's name
        $category->name = $request->input('name');

        // Save the updated unit
        $category->save();

        // Redirect with success message
        return redirect()->route('categories.index')->with('success', 'category updated successfully!');
    }


    public function trash()
    {
        $categories = Category::onlyTrashed()->get();

        return view('admin.managenment.category.categories_trash', compact('categories'));
    }

    public function restore(string $id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('categories.index')->with('success', 'category restore Successfully!!');
    }

    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        $category->forceDelete();
        return redirect()->back()->with('success', 'category Permanent Delete Successfully!!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category moved to trash successfully!');
    }
    public function searches(Request $request)
    {
        $output = '';
        $categories = Category::where('name', 'like', '%' . $request->search . '%')->with('supplier')->get();

        foreach ($categories as $category) {
            $output .= "<tr>
                        <td>{$category->id}</td>
                        <td>{$category->name}</td>
                        <td>{$category->supplier->name}</td>
                        <td>
                            <a href=\"" . route('categories.edit', $category->id) . "\" class=\"btn btn-primary btn-sm\">
                                <i class=\"bx bx-edit-alt\"></i>
                            </a>
                            <form id=\"delete-form-{$category->id}\" action=\"" . route('categories.destroy', $category->id) . "\" method=\"POST\" style=\"display: inline;\">
                                " . csrf_field() . "
                                " . method_field('DELETE') . "
                                <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"confirmDelete({$category->id});\">
                                    <i class=\"fas fa-trash\"></i>
                                </button>
                            </form>
                        </td>
                    </tr>";
        }

        return response($output);
    }


}
