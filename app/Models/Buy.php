<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\BuyProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buy extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = ['purchase_no', 'date', 'supplier_id', 'category_id'];


    /**
     * Get the supplier that owns the purchase.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    /**
     * Get the category that owns the purchase.
     */
    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }

    /**
     * Get the product that owns the purchase.
     */
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'buy_product')
                    ->withPivot('quantity', 'unit_price', 'total_price', 'description')
                    ->withTimestamps();
    }

    public function buyProducts()
{
    return $this->hasMany(BuyProduct::class);
}

}
