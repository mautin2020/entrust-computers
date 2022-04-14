<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaptopProduct extends Model
{
    protected $fillable =[
        'brand_name',
        'description',
        'price',
        'model_no',
        'product_code',
        'processor_manufacturer',
        'processor_info',
        'memory_capacity',
        'storage_type',
        'storage_capacity',
        'graphics_manufacturer',
        'graphics_capacity',
    ];
}