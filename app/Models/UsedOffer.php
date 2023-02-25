<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'offer_id',
        'offer_title',
        'product_id',
        'product_title',
        'category_id',
        'author',
        'min_order',
        'offer_rate',
        'discounted_amount'
    ];
}
