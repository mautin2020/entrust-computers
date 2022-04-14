<?php 

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ILaptopProductLedger 
{
    public function getByProductLedger(Request $request);
    public function productAvailability($productCode);
    public function getLastBalance($productCode);
}