<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $table = 'sales_items'; 
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'amount',
        'remark'
    ];
    
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'invoice_id'); // specify foreign key
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // specify foreign key
    }
}
