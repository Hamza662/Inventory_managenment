<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Concerns\HidesDemoData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class unit extends Model
{
    use HasFactory, HidesDemoData;

    protected $table = 'units';
    protected $fillable = ['name', 'is_demo'];

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
}
