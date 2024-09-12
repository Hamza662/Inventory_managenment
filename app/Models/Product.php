<?php

namespace App\Models;

use App\Models\Buy;
use App\Models\Item;
use App\Models\unit;
use App\Models\Invoice;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\BuyProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = ['name', 'unit_price', 'supplier_id', 'unit_id', 'category_id'];

    public function unit(){
        return $this->belongsTo(unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function categories(){
        
        return $this->hasMany(Category::class);
    }

    public function buy(){
        return $this->hasMany(Buy::class);
    }

    public function buys()
    {
        return $this->belongsToMany(Buy::class, 'buy_product')
                    ->withPivot('quantity', 'unit_price', 'total_price', 'description')
                    ->withTimestamps();
    }

    // public function invoices(){
    //     return $this->hasMany(Invoice::class);  
    //     // return $this->belongsToMany(Invoice::class, 'invoice_product', 'product_id', 'invoice_id');
    // }

    public function buyProducts()
    {
        return $this->hasMany(BuyProduct::class);
    }

    public function invoice()
    {
     return $this->belongsTo(Invoice::class);
    }

    public function items()
{
    return $this->hasMany(Item::class);
}
}
