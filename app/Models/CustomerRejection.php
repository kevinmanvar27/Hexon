<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerRejection extends Model
{
    use HasFactory;

    // Table name (optional if it follows convention)
    protected $table = 'customer_rejections';

    // Mass assignable fields
    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'type',
        'note', // optional note field
    ];

    // Relationships

    // Link to customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Link to product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Link to spare parts
    public function spareParts()
    {
        return $this->hasMany(CustomerRejectionSparePart::class);
    }
}
