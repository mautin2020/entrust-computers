<?php 
namespace App\Repositories\Eloquent;

use App\Models\Supply;
use App\Repositories\Contracts\ISupply;
use App\Repositories\Eloquent\BaseRepository;

class SupplyRepository extends BaseRepository implements ISupply 
{

    public function model()
    {
        return Supply::class;
    }
}