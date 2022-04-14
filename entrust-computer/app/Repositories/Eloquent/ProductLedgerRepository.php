<?php 
namespace App\Repositories\Eloquent;

use Illuminate\Http\Request;
use App\Models\ProductLedger;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\IProductLedger;

class ProductLedgerRepository extends BaseRepository implements IProductLedger 
{

    public function model()
    {
        return ProductLedger::class;
    }

    public function productAvailability($productName)
    {
        return $this->model
                    ->where('product_name', $productName)
                    ->pluck('balance')
                    ->last();
    }

    public function getByProductLedger(Request $request)
            {
                $query = (new $this->model)->newQuery();

                // search by date range
                $dateOne = $request->firstDate;
                $dateTwo = $request->secondDate;

                if($dateOne && $dateTwo){
                    $query->whereBetween('date', [$dateOne, $dateTwo]);
                }

                if($request->productName){
                    $query->where(function($productName) use($request){
                        $productName->where('product_name', 'like', '%'.$request->productName.'%');
                    });
                }

                // searching functionality
                if($request->q){
                $query->where(function($q) use ($request){
                $q->where('particular', 'like', '%'.$request->q.'%')
                    ->orWhere('description', 'like', '%'.$request->q.'%')
                    ->orWhere('closing_stock', 'like', '%'.$request->q.'%')
                    ->orWhere('opening_stock', 'like', '%'.$request->q.'%')
                    ->orWhere('ref_no', 'like', '%'.$request->q.'%')
                    ->orWhere('sales', 'like', '%'.$request->q.'%')
                    ->orWhere('supply', 'like', '%'.$request->q.'%')
                    ->orWhere('balance', 'like', '%'.$request->q.'%');
            });
        }
                    // order the result
                    if($request->orderBy=='opening_stock'){
                        $query->orderByDesc('opening_stock');
                    } else if($request->orderBy=='closing_stock'){
                        $query->orderByDesc('closing_stock');
                    } else if($request->orderBy=='sales'){
                        $query->orderByDesc('sales');
                    } else if($request->orderBy=='supply'){
                        $query->orderByDesc('supply');
                    } else if($request->orderBy=='balance'){
                        $query->orderByDesc('balance');
                    }
                    return $query->get();
                } 
}