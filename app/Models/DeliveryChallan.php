<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryChallan extends Model
{
    protected $fillable = [
        'job_work_name',
        'pdf_files',
        'po_revision_and_date',
        'reason_of_revision',
        'quotation_ref_no',
        'remarks',
        'pr_date',
        'prno',
        'po_no',
        'user_id',
        'customer_id'
    ];
    protected $casts = [
        'pdf_files' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(DeliveryChallanItem::class);    
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
