<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =[
        'product_name',
        'description',
        'price'
    ];

    public function ProductLedgers()
    {
        return $this->hasMany(ProductLedger::class, 'product_name', 'product_name');
    }
    
    public function Supplies()
    {
        return $this->hasMany(Supply::class, 'product_name', 'product_name');
    }
}
