<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Log;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->session()->get('query');

        $suppliers = Supplier::query();

        if ($query) {
            $suppliers = $suppliers->where('name', 'LIKE', "%$query%");
        }

        $suppliers = $suppliers->paginate(5);

        return view('admin.supplier.suppliers_index', compact('suppliers'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.supplier.suppliers_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required'
        ]);

        Supplier::create($data);

        return redirect()->route('suppliers.index')->with('success', 'supplier created successfully!');

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
        $user = Supplier::findOrFail($id);

        return view('admin.supplier.suppliers_edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the supplier by ID or fail if not found
        $supplier = Supplier::findOrFail($id);

        // Validate the request data
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required'
        ]);

        // Update the supplier with the validated data
        $supplier->update($data);

        // Redirect to the suppliers index with a success message
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.trash')->with('success', 'Supplier trash successfully!');
    }

    public function trash()
    {
        try {
            $suppliers = Supplier::onlyTrashed()->get();
            return view('admin.supplier.suppliers_trash', compact('suppliers'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'An error occurred');
        }
    }

    public function restore($id)
    {
        $supplier = Supplier::withTrashed()->findOrFail($id);
        $supplier->restore();

        return redirect()->route('suppliers.index')->with('success', 'Supplier restored successfully.');
    }


    public function ForceDelete($id)
    {
        $supplier = Supplier::withTrashed()->findOrFail($id);

        if ($supplier) {
            $supplier->forceDelete();
        }

        return redirect()->back()->with('success', 'Supplier Permanently Deleted Successfully!');
    }

    // public function search(Request $request)
    // {

    //     $output = '';
    //     $suppliers = Supplier::where('name', 'like', '%' . $request->search . '%')
    //         ->orWhere('email', 'like', '%' . $request->search . '%')
    //         ->orWhere('phone', 'like', '%' . $request->search . '%')
    //         ->orWhere('address', 'like', '%' . $request->search . '%')
    //         ->get();

    //     foreach ($suppliers as $supplier) {
    //         $name = htmlspecialchars($supplier->name, ENT_QUOTES, 'UTF-8');
    //         $email = htmlspecialchars($supplier->email, ENT_QUOTES, 'UTF-8');
    //         $phone = htmlspecialchars($supplier->phone, ENT_QUOTES, 'UTF-8');
    //         $address = htmlspecialchars($supplier->address, ENT_QUOTES, 'UTF-8');

    //         $output .= "<tr>
    //                         <td>{$supplier->id}</td>
    //                         <td>{$supplier->name}</td>
    //                         <td>{$supplier->email}</td>
    //                         <td>{$supplier->phone}</td>
    //                         <td>{$supplier->address}</td>
    //                         <td>
    //                             <a href=\"" . route('suppliers.edit', $supplier->id) . "\" class=\"btn btn-primary btn-sm\">
    //                                 <i class=\"bx bx-edit-alt\"></i> 
    //                             </a>
    //                             <form id=\"delete-form-{$supplier->id}\" action=\"" . route('suppliers.destroy', $supplier->id) . "\" method=\"POST\" style=\"display: inline;\">
    //                                 " . csrf_field() . "
    //                                 " . method_field('DELETE') . "
    //                                 <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"confirmDelete({$supplier->id});\">
    //                                     <i class=\"fas fa-trash\"></i> 
    //                                 </button>
    //                             </form>
    //                         </td>
    //                     </tr>";
    //     }

    //     return response($output);
    // }



}
