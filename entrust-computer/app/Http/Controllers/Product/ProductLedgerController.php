<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ILaptopProductLedger;
use App\Http\Resources\LaptopProductLedgerResource;

class ProductLedgerController extends Controller
{
    protected $laptopProductledgers;

    public function __construct(ILaptopProductLedger $laptopProductledgers)
    {
        $this->laptopProductledgers = $laptopProductledgers;
    }

    public function getProductLedger(Request $request)
    {
        $getProductLedger = $this->laptopProductledgers->getByProductLedger($request);
        return LaptopProductLedgerResource::collection($getProductLedger);
    }

    public function productBalance($productCode)
    {
        $balance = $this->laptopProductledgers->findWhere('product_code', $productCode)
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
            'product_code' => ['required', 'string'],
            'date' => ['required', 'date'],
            'closing_stock' => ['required', 'integer']
        ]);

        $closingstocks = $request->closing_stock;

        if($this->laptopProductledgers->productAvailability($request['product_code']) === 0){
            return response()->json([
                'message' => 'Product is out of stock'
            ], 422);
        }

        $ProductAvailable = $this->laptopProductledgers->productAvailability($request->product_code);

        if($ProductAvailable < $closingstocks){
            return response()->json([
                'message' => 'Closing Stock greater than product available'
            ], 422);
        }
        
        $sales = $ProductAvailable - $closingstocks;

        $particular = 'Closing Stock added';
        $description = 'Closing Stock as at ' .' '. $request->date;
        $ref_no = time();

        $closingStock = $this->laptopProductledgers->create([
            'product_code' => $request->product_code,
            'date' => $request->date,
            'opening_stock' => $ProductAvailable,
            'closing_stock' => $request->closing_stock,
            'sales' => $sales,
            'particular' => $particular,
            'description' => $description,
            'ref_no' => $ref_no,
            'balance' => $request['closing_stock']

        ]);

        return new LaptopProductLedgerResource($closingStock);
    }
}
