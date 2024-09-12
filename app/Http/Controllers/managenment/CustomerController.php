<?php

namespace App\Http\Controllers\managenment;

use App\Models\customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->session()->get('query');

        $customers = customer::query();

        if($query){
            $customers = $customers->where('name', 'LIKE', "%$query%");
        }
        $customers = $customers->paginate(5);

        return view('admin.managenment.customer.customers_index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.managenment.customer.customers_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required'
        ]);

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $fileName = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('image/admin_images'), $fileName);
            $data['photo'] = $fileName;
        }
        customer::create($data);

        return redirect()->route('customers.index')->with('success', 'customer created successfully!');
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
        $customer = customer::findOrFail($id);

        return view('admin.managenment.customer.customers_edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = customer::findOrFail($id);
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ]);

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $fileName = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('image/admin_images'), $fileName);
            $data['photo'] = $fileName;
        }

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'customer updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer trash successfully!');
    }

    public function trash()
    {
        $customers = customer::onlyTrashed()->get();

        return view('admin.managenment.customer.customers_trash', compact('customers'));
    }

    public function restore(string $id)
    {
        $customer = customer::withTrashed()->findOrFail($id);

        if (!is_null($customer)) {
            $customer->restore();
        }
        return redirect()->route('customers.index')->with('success', 'customer restore Successfully!!');
    }

    public function ForceDelete($id)
    {
        $customer = customer::withTrashed()->findOrFail($id);

        if (!is_null($customer)) {
            $customer->forceDelete();
        }
        return redirect()->back()->with('success', 'customer Permanent Delete Successfully!!');
    }

    public function search(Request $request)
    {
        $output = '';
        $customers = customer::where('name', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%')
            ->orWhere('address', 'like', '%' . $request->search . '%')
            ->get();

        foreach ($customers as $customer) {
            $name = htmlspecialchars($customer->name, ENT_QUOTES, 'UTF-8');
            $email = htmlspecialchars($customer->email, ENT_QUOTES, 'UTF-8');
            $address = htmlspecialchars($customer->address, ENT_QUOTES, 'UTF-8');

            $output .= "<tr>
                            <td>{$customer->id}</td>
                            <td>{$customer->name}</td>
                            <td>{$customer->email}</td>
                            <td>{$customer->address}</td>
                            <td>
                                <a href=\"" . route('customers.edit', $customer->id) . "\" class=\"btn btn-primary btn-sm\">
                                    <i class=\"bx bx-edit-alt\"></i> 
                                </a>
                                <form id=\"delete-form-{$customer->id}\" action=\"" . route('customers.destroy', $customer->id) . "\" method=\"POST\" style=\"display: inline;\">
                                    " . csrf_field() . "
                                    " . method_field('DELETE') . "
                                    <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"confirmDelete({$customer->id});\">
                                        <i class=\"fas fa-trash\"></i> 
                                    </button>
                                </form>
                            </td>
                        </tr>";
        }

        return response($output);
    }
}
