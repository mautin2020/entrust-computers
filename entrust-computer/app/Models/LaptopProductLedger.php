<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaptopProductLedger extends Model
{
    protected $fillable = [
        'date',
        'product_code',
        'opening_stock',
        'closing_stock',
        'description',
        'particular',
        'ref_no',
        'sales',
        'supply',
        'balance',
    ];
}
