<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ResponseAPI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPUnit\Exception;
use PHPUnit\Framework\Constraint\Count;

class OrderController extends Controller
{
    use ResponseAPI;

    public function order(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(),[
                'products' => 'required'
            ]);
            if ($validator->fails())
                return $this->error(message: $validator->errors(), statusCode:422);

            $user = Auth::user();
            $order_id = Str::random(10);

            $productData=[];
            $responseData=[];
            $products = $request["products"];

            foreach ($products as $product){
                $productIdData[] = $product["product_id"];
                $productQuantityData[] = $product["quantity"];
            }

            // Stock Control
            $outOfStock =[];
            for ($i=0; $i < count($productIdData) ;$i++){
                $product = Product::where('product_id', $productIdData[$i])->first();
                if ($product->stock_quantity < $productQuantityData[$i]) {
                    $outOfStock[] = $productIdData[$i];
                }
            }
            if (count($outOfStock)>0){
                $data = [
                    'Out Of Stock Product Id' => $outOfStock
                ];
                return $this->success(message: "The product sold out..", data: $data);
            }

            // Create Order
            for ($i=0; $i < count($productIdData) ;$i++){
                $product = Product::where('product_id', $productIdData[$i])->first();
                $product_price = $product->list_price;
                $data = [
                    'order_id' => $order_id,
                    'user_id' => $user->id,
                    'product_id' => trim($productIdData[$i]),
                    'quantity' => $productQuantityData[$i],
                    'order_price' => $product_price
                ];
                $responseData[] = $data;
                $create = Order::create($data);
            }

            // Decrease Quantity
            if ($create){
                for ($i=0; $i < count($productIdData) ;$i++){
                    DB::table('products')->where('product_id', $productIdData[$i])->decrement('stock_quantity', $productQuantityData[$i]);
                }
            }

            return $this->success(message: "Order created successfully.", data: $responseData);
        }catch (Exception $e){
            return $this->error(message: $e->getMessage(), statusCode: $e->getCode());
        }
    }
}