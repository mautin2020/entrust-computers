<?php

namespace App\Http\Controllers\Product;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LaptopProductResource;
use App\Models\LaptopProductLedger;
use App\Repositories\Contracts\ILaptopProduct;
use App\Repositories\Contracts\ILaptopProductLedger;

class LaptopProductController extends Controller
{
    protected $laptops;
    protected $laptopProductLedgers;

    public function __construct(ILaptopProduct $laptops,
                                ILaptopProductLedger $laptopProductLedgers)
    {
        $this->laptops = $laptops;
        $this->laptopProductLedgers = $laptopProductLedgers;
    }

    public function index()
    {
        $getallLaptopProduct = $this->laptops->all();
        return LaptopProductResource::collection($getallLaptopProduct);
    }

    public function getLaptopProductByID($id)
    {
        $getLaptopProductByID = $this->laptops->find($id);
        return new LaptopProductResource($getLaptopProductByID);
    }

    public function addLaptopProduct(Request $request)
    {
        $this->validate($request, [
            'brand_name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'model_no' => ['required', 'string'],
            'processor_manufacturer' => ['required', 'string'],
            'processor_info' => ['required', 'string'],
            'memory_capacity' => ['required', 'string'],
            'storage_type' => ['required', 'string'],
            'storage_capacity' => ['required', 'string'],
            'graphics_manufacturer' => ['required', 'string'],
            'graphics_capacity' => ['required', 'string'],
            'opening_stock' => ['required', 'integer']
        ]);

        $randomNo = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        
        $productCode = $request['model_no'].'-'.$randomNo;

        $laptopProduct = $this->laptops->create([
            'brand_name' => $request['brand_name'],
            'description' => $request['description'],
            'product_code' => $productCode,
            'price' => $request['price'],
            'model_no' => $request['model_no'],
            'processor_manufacturer' => $request['processor_manufacturer'],
            'processor_info' => $request['processor_info'],
            'memory_capacity' => $request['memory_capacity'],
            'storage_type' => $request['storage_type'],
            'storage_capacity' => $request['storage_capacity'],
            'graphics_manufacturer' => $request['graphics_manufacturer'],
            'graphics_capacity' => $request['graphics_capacity'],
        ]);

        $particular = $request['brand_name'] . ' ' . $request['processor_manufacturer'] .' '. $request['processor_info'] .' '. 'with' . ' '. $request['memory_capacity'] . ' RAM ' . ' added to product list' ;
        $ref_no = time() . '-' . $laptopProduct->id;

        $laptopProductLedger = $this->laptopProductLedgers->create([
            'product_code' => $productCode,
            'opening_stock' => $request->opening_stock,
            'particular' => $particular,
            'description' => $request->description,
            'date' => Carbon::now(),
            'ref_no' => $ref_no,
            'balance' => $request->opening_stock
        ]);

        return new LaptopProductResource($laptopProduct);
    }

    public function updateLaptopProduct(Request $request, $id)
    {
        $this->validate($request, [
            'brand_name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'model_no' => ['required', 'string'],
            'processor_manufacturer' => ['required', 'string'],
            'processor_info' => ['required', 'string'],
            'memory_capacity' => ['required', 'string'],
            'storage_type' => ['required', 'string'],
            'storage_capacity' => ['required', 'string'],
            'graphics_manufacturer' => ['required', 'string'],
            'graphics_capacity' => ['required', 'string'],
            'opening_stock' => ['required', 'integer'],
            'ref_no' => ['required', 'string']
        ]);

        $laptopProducts = $this->laptops->find($id);

        $laptopProduct = $this->laptops->update($id, [
            'brand_name' => $request['brand_name'],
            'description' => $request['description'],
            'price' => $request['price'],
            'model_no' => $request['model_no'],
            'processor_manufacturer' => $request['processor_manufacturer'],
            'processor_info' => $request['processor_info'],
            'memory_capacity' => $request['memory_capacity'],
            'storage_type' => $request['storage_type'],
            'storage_capacity' => $request['storage_capacity'],
            'graphics_manufacturer' => $request['graphics_manufacturer'],
            'graphics_capacity' => $request['graphics_capacity'],
        ]);

        // create update function later
        //   $laptop = tap(LaptopProductLedger::where('ref_no', $request['ref_no']))->update(['opening_stock' => $request['opening_stock']]);
        
            return new LaptopProductResource($laptopProduct);
    }

    public function destroy($id)
    {
        $product = $this->laptops->find($id);
       
        $this->laptops->delete($id);
        return response()->json(['message' => 'Product deleted'], 200);
    }
}