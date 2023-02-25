<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function createReceipt($orderProducts){
        $shipmentFee=75;
        $totalFee=0;
        $discountedTotalFee=0;

        // Total Fee
        foreach ($orderProducts as $item){
            $producutPrice = $item['order_price'];
            $producutQuantity = $item['quantity'];
            $oneProductFee=$producutPrice*$producutQuantity;
            $totalFee += $oneProductFee;
            $orderId = $item['order_id'];
        }

        // Is Shipment Free
        if ($totalFee >= 200){
            $shipmentFee=0;
        }

        $data = [
            'order_id' => $orderId,
            'shipment_fee' => $shipmentFee,
            'discounted_fee' => 0,
            'all_products_fee' => $totalFee,
            'total_fee' => $totalFee+$shipmentFee,
            'discounted_total_fee' => 0
        ];
        Receipt::create($data);

        return $data;
    }
}
