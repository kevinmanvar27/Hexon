<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewPurchaseOrderItem extends Model
{
    protected $table = 'new_purchase_order_item'; 
    protected $fillable = [
        'new_purchase_order_id',
        'spare_part_id',
        'quantity',
        'remaining_quantity',
        'price',
        'amount',
        'product_unit',
        'remark',
        'material_specification',
        'unit',
        'rate_kgs',
        'per_pc_weight',
        'total_weight',
        'delivery_date',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(NewPurchaseOrder::class, 'new_purchase_order_id');
    }

    public function sparePart()
    {
        return $this->belongsTo(SpareParts::class);
    }
}
