<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\CreditCustomer;
use App\Models\Concerns\HidesDemoData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class customer extends Model
{
    use HasFactory, SoftDeletes, HidesDemoData;
    protected $guarded = [];


    public function creditCustomers()
    {
        return $this->hasMany(CreditCustomer::class);
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
}
