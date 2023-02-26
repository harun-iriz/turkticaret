<?php

namespace App\Http\Controllers;

use App\Events\ProductCreated;
use App\Models\Order;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\UsedOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ResponseAPI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPUnit\Exception;
use PHPUnit\Framework\Constraint\Count;
use App\Http\Controllers\ReceiptController;

class OrderController extends Controller
{
    use ResponseAPI;

    public function __construct(){
        Event::dispatch(new ProductCreated());
        Cache('products', function (){
            return Product::get();
        });
    }

    public function createOrder(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(),[
                'products' => 'required'
            ]);
            if ($validator->fails())
                return $this->error(message: $validator->errors(), statusCode:422);

            $user = Auth::user();
            $order_id = Str::random(10);

            $products = $request["products"];

            foreach ($products as $product){
                $productIdData[] = $product["product_id"];
                $productQuantityData[] = $product["quantity"];
            }

            // Stock Control
            $outOfStock =[];
            for ($i=0; $i < count($productIdData) ;$i++){
                $id = $productIdData[$i];
                $product = Cache::get('products')->where('product_id', $id);
                foreach ($product as $object){
                    if ($object->stock_quantity < $productQuantityData[$i]) {
                        $outOfStock[] = $productIdData[$i];
                    }
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
                $id = $productIdData[$i];
                $product = Cache::get('products')->where('product_id', $id);

                foreach ($product as $object){
                    $product_price = $object->list_price;
                    $data = [
                        'order_id' => $order_id,
                        'user_id' => $user->id,
                        'product_id' => trim($productIdData[$i]),
                        'product_title' => $object->title,
                        'category_id' => $object->category_id,
                        'category_title' => $object->category_title,
                        'author' => $object->author,
                        'quantity' => $productQuantityData[$i],
                        'order_price' => $product_price
                    ];
                    $orderProducts[] = $data;
                    $create = Order::create($data);
                }
            }

            // Decrease Quantity
            if ($create){
                for ($i=0; $i < count($productIdData) ;$i++){
                    DB::table('products')->where('product_id', $productIdData[$i])->decrement('stock_quantity', $productQuantityData[$i]);
                }
            }

            $receipt = (new ReceiptController)->createReceipt($orderProducts);

            $responseData = [
                'order_products' => $orderProducts,
                'receipt' => $receipt
            ];

            return $this->success(message: "Order created successfully.", data: $responseData);
        }catch (Exception $e){
            return $this->error(message: $e->getMessage(), statusCode: $e->getCode());
        }
    }

    public function showOrderDetails($order_id){
        try {
            $user = Auth::user();

            $productsData = [];
            $products = Order::where('user_id', $user->id)->where('order_id', $order_id)->get();
            foreach ($products as $product){
                $productsData[] = [
                    'order_id' => $product->order_id,
                    'product_id' => $product-> product_id,
                    'product_title' => $product-> product_title,
                    'category_id' => $product-> category_id,
                    'category_title' => $product-> category_title,
                    'author' => $product-> author,
                    'quantity' => $product-> quantity,
                    'order_price' => $product-> order_price
                ];
            }

            $receipt = Receipt::where('order_id', $order_id)->first();
            $receiptData = [
                'order_id' => $receipt->order_id,
                'shipment_fee' => $receipt->shipment_fee,
                'discounted_fee' => $receipt->discounted_fee,
                'all_products_fee' => $receipt->all_products_fee,
                'total_fee' => $receipt->total_fee,
                'discounted_total_fee' => $receipt->discounted_total_fee
            ];

            $offer = UsedOffer::where('order_id', $order_id)->first();
            $offerData = [
                'order_id' => $offer->offer_id,
                'offer_id' => $offer->offer_id,
                'offer_title' =>$offer->offer_title,
                'products_id' => $offer->product_id,
                'products_title' => $offer->products_title,
                'category_id' => $offer->category_id,
                'author' => $offer->author,
                'min_order' => $offer->min_order,
                'offer_rate' => $offer->offer_rate,
                'discounted_amount' => $offer->discounted_amount
            ];

            $responseData = [
                'products' => $productsData,
                'receipt' => $receiptData,
                'offer' => $offerData
            ];

            return $this->success(message: "The order is showing successfully.", data: $responseData);
        }catch (Exception $e){
            return $this->error(message: $e->getMessage(), statusCode: $e->getCode());
        }
    }
}
