<?php 
namespace App\Repositories\Eloquent;

use App\Models\LaptopProduct;
use App\Repositories\Contracts\ILaptopProduct;
use App\Repositories\Eloquent\BaseRepository;

class LaptopProductRepository extends BaseRepository implements ILaptopProduct 
{

    public function model()
    {
        return LaptopProduct::class;
    }
}