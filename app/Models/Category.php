<?php

namespace App\Models;

use App\Models\Buy;
use App\Models\Item;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'categories';
    protected $fillable = ['name', 'supplier_id'];


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function productsViaPivot()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function buy()
    {
        return $this->hasMany(Buy::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}
