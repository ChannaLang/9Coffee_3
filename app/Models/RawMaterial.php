<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        'id',       // ✅ allow manual ID input
        'name',
        'quantity',
        'unit'
    ];

    public $incrementing = false;  // ✅ prevent auto-increment
    protected $keyType = 'int';    // ✅ ID is an integer key

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_raw_material')
                    ->withPivot('quantity_required')
                    ->withTimestamps();
    }
}
