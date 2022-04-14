<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLedger extends Model
{
    protected $fillable =[
        'product_name',
        'date',
        'opening_stock',
        'closing_stock',
        'description',
        'particular',
        'ref_no',
        'supply',
        'sales',
        'balance'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_name', 'product_name');
    }
}
