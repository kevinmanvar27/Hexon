<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewPurchaseOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice',
        'status',
        'po_revision_and_date',
        'reason_of_revision',
        'quotation_ref_no',
        'remarks',
        'prno',
        'pr_date',
        'address',
        'note'
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function items()
    {
        return $this->hasMany(NewPurchaseOrderItem::class);
    }
}
