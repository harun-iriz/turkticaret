<?php

namespace App\Http\Controllers;

use App\Events\OfferCreated;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cache;

class OfferController extends Controller
{
    public function __construct(){
        Event::dispatch(new OfferCreated());
        Cache('products', function (){
            return Offer::get();
        });
    }

    public function offerCheck($orderProducts,$totalFee){

        $offer1Data = $this->offerOneCheck($orderProducts,$totalFee);
        $offer2Data = $this->offerTwoCheck($orderProducts,$totalFee);

        if ($offer1Data['discounted_amount'] > $offer2Data['discounted_amount']){
            $bestOffer = $offer1Data;
        }else{
            $bestOffer = $offer2Data;
        }

        return $bestOffer;
    }

    // For Offer Id = 1;
    public function offerOneCheck($orderProducts,$totalFee){

        $offer1 = Cache::get('offers')->where('offer_id', 1);

        $offer1CheckDiscount= 0;
        $offer1DiscountedFee= 0;
        $response1Data = ['discounted_amount' => 0];

        foreach ($offer1 as $item) {
            $counter = 0;
            $author = $item->author;
            $category_id = $item->category_id;
            foreach ($orderProducts as $product){
                if (trim($product['author']) == trim($author) && $product['category_id'] == $category_id) {
                    $counter += $product['quantity'];
                    $offer1CheckDiscount = $totalFee - $product['order_price'];
                    if ($offer1CheckDiscount > $offer1DiscountedFee) {
                        $offer1DiscountedFee = $offer1CheckDiscount;
                        $offer1Data = [
                            'order_id' => $orderProducts[0]['order_id'],
                            'offer_id' => $item->id,
                            'offer_title' => $item->offer_title,
                            'product_id' => $product['product_id'],
                            'product_title' => $product['product_title'],
                            'category_id' => $product['category_id'],
                            'category_title' => $product['category_title'],
                            'author' => $product['author'],
                            'min_order' => null,
                            'offer_rate' => null,
                            'discounted_amount' => $product['order_price']
                        ];
                    }
                }
            }
            $offer1DiscountedFee=0;
            if ($counter > 1) {
                if ($offer1CheckDiscount > $offer1DiscountedFee){
                    $offer1DiscountedFee = $offer1CheckDiscount;
                    $response1Data = $offer1Data;
                }
            }
        }
        return $response1Data;
    }

    // For Offer Id = 2;
    public function offerTwoCheck($orderProducts,$totalFee){

        $offer2 = Cache('offers', function (){
            return Offer::where('offer_id', 2)->get();
        });
        $offer2 = Cache::get('offers')->where('offer_id', 2);

        $offer2Data = ['discounted_amount' => 0];
        $offer2CheckDiscount=0;
        $offer2DiscountedFee=0;
        foreach ($offer2 as $item) {
            if ($totalFee >= $item->min_order) {
                $offer2CheckDiscount = $totalFee - ($totalFee * ($item->offer_rate) / 100);
                if ($offer2CheckDiscount > $offer2DiscountedFee){
                    $offer2DiscountedFee = $offer2CheckDiscount;
                    $offer2Data = [
                        'order_id' => $orderProducts[0]['order_id'],
                        'offer_id' => $item->id,
                        'offer_title' => $item->offer_title,
                        'product_id' => null,
                        'product_title' => null,
                        'category_id' => null,
                        'category_title' => null,
                        'author' => null,
                        'min_order' => $item->min_order,
                        'offer_rate' => $item->offer_rate,
                        'discounted_amount' => $totalFee-$offer2DiscountedFee,
                    ];
                }
            }
        }
        return $offer2Data;
    }
}
