<?php 
namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\IProduct;
use App\Repositories\Eloquent\BaseRepository;

class ProductRepository extends BaseRepository implements IProduct 
{

    public function model()
    {
        return Product::class;
    }
}