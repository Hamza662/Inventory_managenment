<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Payment;
use App\Models\customer;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function customer()
    {
        return $this->belongsTo(customer::class, 'customer_id');
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function unit()
    {
        return $this->belongsTo(unit::class, 'unit_id');
    }
    public function item()
    {
        return $this->hasOne(Item::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
