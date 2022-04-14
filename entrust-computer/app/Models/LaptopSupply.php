<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaptopSupply extends Model
{
    protected $fillable = [
        'supplier_name',
        'product_name',
        'quantity',
        'cost_price',
        'amount',
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_name', 'product_name');
    }
}