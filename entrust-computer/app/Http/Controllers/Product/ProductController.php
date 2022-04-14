<?php

namespace App\Http\Controllers\Product;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\Contracts\IProduct;
use App\Repositories\Contracts\IProductLedger;

class ProductController extends Controller
{
    protected $products;
    protected $productLedgers;

    public function __construct(IProduct $products,
                                IProductLedger $productLedgers)
    {
        $this->products = $products;
        $this->productLedgers = $productLedgers;
    }

    public function index() 
    {
        $getallProduct = $this->products->all();
        return ProductResource::collection($getallProduct);
    }

    public function getProductById($id)
    {
        $productId = $this->products->find($id);
        return new ProductResource($productId);
    }

    public function createProduct(Request $request)
    {
        $this->validate($request, [
            'product_name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'opening_stock' => ['required', 'string']
        ]);

        $product = $this->products->create([
            'product_name' => $request['product_name'],
            'description' => $request['description'],
            'price' => $request['price'],
        ]);

        $particular = $request['product_name'] .' ' .'added to product list' ;
        $ref_no = time() . '-' . $product->id;

        $productledger = $this->productLedgers->create([
            'product_name' => $request->product_name,
            'description' => $request['description'],
            'particular' => $particular,
            'date' => Carbon::now(),
            'opening_stock' => $request->opening_stock,
            'ref_no' => $ref_no,
            'balance' => $request->opening_stock
            ]);

            return new ProductResource($product);
    }

    public function updateProduct(Request $request, $id)
    {
        $this->validate($request, [
            'product_name' => ['required', 'string'],
            'price' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        $product = $this->products->find($id); 

        $product = $this->products->update($id,[
            'product_name' => $request['product_code'],
            'price' => $request['price'],
            'description' => $request['description'],
        ]);

        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $product = $this->products->find($id);
       
        $this->products->delete($id);
        return response()->json(['message' => 'Product deleted'], 200);
    }
}