<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\Contracts\IProductLedger;
use App\Http\Resources\GeneralProductLedgerResource;

class GeneralProductLedgerController extends Controller
{
    protected $productledgers;

    public function __construct(IProductLedger $products)
    {
        $this->productledgers = $products;
    }

    public function getGeneralProductLedger(Request $request)
    {
        $getGeneralProductLedger = $this->productledgers->getByProductLedger($request);
        return ProductResource::collection($getGeneralProductLedger);
    }

    public function productBalance($productName)
    {
        $balance = $this->productledgers->findWhere('product_name', $productName)
                                        ->pluck('balance')
                                        ->last();

        $finalBalance = number_format($balance);
        
                                        
        return response()->json([
            'balance' => $finalBalance
        ]);
    }

    public function closingStock(Request $request)
    {
        $this->validate($request, [
            'product_name' => ['required', 'string'],
            'date' => ['required', 'date'],
            'closing_stock' => ['required', 'integer']
        ]);

        $closingstocks = $request->closing_stock;

        if($this->productledgers->productAvailability($request['product_name']) === 0){
            return response()->json([
                'message' => 'Product is out of stock'
            ], 422);
        }

        $ProductAvailable = $this->productledgers->productAvailability($request->product_name);

        if($ProductAvailable < $closingstocks){
            return response()->json([
                'message' => 'Closing Stock greater than product available'
            ], 422);
        }
        
        $sales = $ProductAvailable - $closingstocks;

        $particular = 'Closing Stock added';
        $description = 'Closing Stock as at ' .' '. $request->date;
        $ref_no = time();

        $closingStock = $this->productledgers->create([
            'product_name' => $request->product_name,
            'date' => $request->date,
            'opening_stock' => $ProductAvailable,
            'closing_stock' => $request->closing_stock,
            'sales' => $sales,
            'particular' => $particular,
            'description' => $description,
            'ref_no' => $ref_no,
            'balance' => $request['closing_stock']

        ]);

        return new GeneralProductLedgerResource($closingStock);
    }
}
