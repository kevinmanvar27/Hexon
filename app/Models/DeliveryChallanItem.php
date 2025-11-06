<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryChallanItem extends Model
{
    protected $fillable = [
        'delivery_challan_id',
        'spare_part_id',
        'quantity',
        'remaining_quantity',
        'wt_pc',
        'material_specification',
        'remark',
    ];
    public function deliveryChallan()
    {
        return $this->belongsTo(DeliveryChallan::class, 'delivery_challan_id');
    }

    public function sparePart()
    {
        return $this->belongsTo(SpareParts::class);
    }
}
