<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRejectionSparePart extends Model
{
    protected $table = 'customer_rejection_spare_parts';

    protected $fillable = [
        'customer_rejection_id',
        'spare_part_id',
        'type',
        'size',
        'weight',
        'quantity',
    ];

    public function rejection()
    {
        return $this->belongsTo(CustomerRejection::class, 'customer_rejection_id');
    }

    public function sparePart()
    {
        return $this->belongsTo(SpareParts::class, 'spare_part_id');
    }
}