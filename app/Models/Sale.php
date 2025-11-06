<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice',
        'status',
        'create_date',
        'due_date',
        'orderno',
        'lrno',
        'transport',
        'address',
        'note',
        'sub_total',
        'pfcouriercharge',
        'discount',
        'discount_type',
        'cgst',
        'sgst',
        'igst',
        'courier_charge',
        'round_off',
        'balance'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
    public function items()
    {
        return $this->hasMany(SaleItem::class, 'invoice_id'); // link via invoice_id
    }
}
