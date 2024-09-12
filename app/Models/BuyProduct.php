<?php

namespace App\Models;

use App\Models\Buy;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BuyProduct extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'buy_product';

    protected $fillable = [
        'buy_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'description',
    ];

    public function buy()
    {
        return $this->belongsTo(Buy::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
 {
    return $this->belongsTo(Category::class);
 }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
}
