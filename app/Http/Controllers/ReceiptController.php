<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\UsedOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\OfferController;

class ReceiptController extends Controller
{
    public function createReceipt($orderProducts){
        $shipmentFee=75;
        $totalFee=0;

        // Total Fee
        foreach ($orderProducts as $item){
            $productPrice = $item['order_price'];
            $producutQuantity = $item['quantity'];
            $oneProductFee=$productPrice*$producutQuantity;
            $totalFee += $oneProductFee;
            $orderId = $item['order_id'];
        }

        $bestOffer = (new OfferController)->offerCheck($orderProducts,$totalFee);

        // Is Shipment Free
        if ($bestOffer['discounted_amount'] > 0){
            if ($totalFee - $bestOffer['discounted_amount'] >= 200){
                $shipmentFee=0;
            }
        }elseif ($totalFee >= 200){
            $shipmentFee=0;
        }

        $data = [
            'order_id' => $orderId,
            'shipment_fee' => $shipmentFee,
            'discounted_fee' => $bestOffer['discounted_amount'],
            'all_products_fee' => $totalFee,
            'discounted_total_fee' => $totalFee - $bestOffer['discounted_amount'],
            'total_fee' => $totalFee + $shipmentFee - $bestOffer['discounted_amount']
        ];
        Receipt::create($data);

        $data2 = [
            'order_id' => $orderId,
            'offer_id' => $bestOffer['offer_id'],
            'offer_title' => $bestOffer['offer_title'],
            'product_id' => $bestOffer['product_id'],
            'product_title' => $bestOffer['product_title'],
            'category_id' => $bestOffer['category_id'],
            'category_title' => $bestOffer['category_title'],
            'author' => $bestOffer['author'],
            'min_order' => $bestOffer['min_order'],
            'offer_rate' => $bestOffer['offer_rate'],
            'discounted_amount' => $bestOffer['discounted_amount']
        ];
        UsedOffer::create($data2);
        
        $responseData = [
            'receipt' => $data,
            'discount' => $data2
        ];

        return $responseData;
    }
}
