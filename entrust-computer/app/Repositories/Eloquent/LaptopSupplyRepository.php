<?php 
namespace App\Repositories\Eloquent;

use App\Models\LaptopSupply;
use App\Repositories\Contracts\ILaptopSupply;
use App\Repositories\Eloquent\BaseRepository;

class LaptopSupplyRepository extends BaseRepository implements ILaptopSupply 
{

    public function model()
    {
        return LaptopSupply::class;
    }
}