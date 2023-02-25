<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shipment_fee',
        'discounted_fee',
        'all_products_fee',
        'total_fee',
        'discounted_total_fee'
    ];
}
