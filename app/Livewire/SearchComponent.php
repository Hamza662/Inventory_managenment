<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;

class SearchComponent extends Component
{
    public $search = '';

    // public function updatedSearch()
    // {
    //     if (trim($this->search)) {
    //         // Redirect based on search input
    //         $supplier = Supplier::where('name', 'like', '%' . $this->search . '%')->first();
    //         if ($supplier) {
    //             return redirect()->route('suppliers.index');
    //         }

    //         $category = Category::where('name', 'like', '%' . $this->search . '%')->first();
    //         if ($category) {
    //             return redirect()->route('categories.index');
    //         }

    //         $product = Product::where('name', 'like', '%' . $this->search . '%')->first();
    //         if ($product) {
    //             return redirect()->route('products.index');
    //         }

    //         if (is_numeric($this->search)) {
    //             $product = Product::find($this->search);
    //             if ($product) {
    //                 return redirect()->route('products.index');
    //             }

    //             $supplier = Supplier::find($this->search);
    //             if ($supplier) {
    //                 return redirect()->route('suppliers.index');
    //             }

    //             $category = Category::find($this->search);
    //             if ($category) {
    //                 return redirect()->route('categories.index');
    //             }
    //         }
    //     }
    // }

    // public function render()
    // {
    //     $products = Product::query()
    //         ->when($this->search, function($query) {
    //             $query->where(function($query) {
    //                 $query->where('name', 'like', '%' . $this->search . '%')
    //                       ->orWhere('id', $this->search)
    //                       ->orWhereHas('supplier', function($q) {
    //                           $q->where('name', 'like', '%' . $this->search . '%');
    //                       })
    //                       ->orWhereHas('category', function($q) {
    //                           $q->where('name', 'like', '%' . $this->search . '%');
    //                       });
    //             });
    //         })
    //         ->get();

    //     return view('livewire.search-component', [
    //         'products' => $products,
    //     ]);
    // }
}
