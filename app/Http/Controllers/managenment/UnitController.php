<?php

namespace App\Http\Controllers\managenment;

use App\Models\unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = unit::get();
        return view('admin.managenment.unit.units_index',compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.managenment.unit.units_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
       $unit =  $request->validate([
            'name' => 'required'
        ]); 

        unit::create($unit);

        return redirect()->route('units.index')->with('success', 'unit created successfully!');
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
        $unit = unit::findOrFail($id);

        return view('admin.managenment.unit.units_edit',compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the unit or fail if not found
        $unit = Unit::findOrFail($id); 
    
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        // Update the unit's name
        $unit->name = $request->input('name');
        
        // Save the updated unit
        $unit->save();
    
        // Redirect with success message
        return redirect()->route('units.index')->with('success', 'Unit updated successfully!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = unit::findOrFail($id);

        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted successfully!');
    }
}
