<?php

namespace App\Models;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class ProductRawMaterial extends Model
{
    protected $table = 'product_raw_materials'; // your table name
    public $timestamps = false; // if your pivot table has no timestamps

    protected $fillable = [
        'product_id',
        'raw_material_id',
        'quantity',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
