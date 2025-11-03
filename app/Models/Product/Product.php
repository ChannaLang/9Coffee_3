<?php

namespace App\Models\Product;
use App\Models\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

  protected $fillable = [
    "name",
    "image",
    "price",
    "description",
    "type",
    //"quantity", // <- add this
];

     // Relationship to raw materials
    public function rawMaterials()
{
    return $this->belongsToMany(RawMaterial::class, 'product_raw_material')
                ->withPivot('quantity_required')
                ->withTimestamps();
}


    // Relationship to orders
    public function orders()
    {
        return $this->hasMany(\App\Models\Product\Order::class);
    }

    
    public  $timestamps = false;
}
