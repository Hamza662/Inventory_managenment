<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
use App\Models\Concerns\HidesDemoData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes, HidesDemoData;
    protected $guarded = [];

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
