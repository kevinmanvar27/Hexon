<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'valve_type',
        'product_code',
        'actuation',
        'pressure_rating',
        'valve_size',
        'valve_size_rate',
        'media',
        'flow',
        'sku_code',
        'mrp',
        'media_temperature',
        'media_temperature_rate',
        'body_material',
        'hsn_code',
        'primary_material_of_construction',
    ];
    public function spareParts()
    {
        return $this->belongsToMany(SpareParts::class, 'product_spare_part', 'product_id', 'spare_part_id')
                    ->withPivot('qty')
                    ->withTimestamps();
    }
    protected static function booted()
    {
        static::deleting(function ($product) {
            $product->spareParts()->detach();
        });
    }
    
}
