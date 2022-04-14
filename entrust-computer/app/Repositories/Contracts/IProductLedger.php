<?php 

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface IProductLedger 
{
    public function getByProductLedger(Request $request); 
    public function productAvailability($productName);  
}