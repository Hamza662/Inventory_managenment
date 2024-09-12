<?php

namespace App\Models;

use App\Models\Buy;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory , SoftDeletes;

    protected $guarded = [];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function productsViaPivot()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    public function buy(){
        return $this->hasMany(Buy::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
