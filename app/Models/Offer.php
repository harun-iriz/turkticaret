<?php

namespace App\Models;

use App\Events\OfferCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $dispatchesEvents = [
        'created' => OfferCreated::class
    ];
    protected $fillable = [
        'offer_id',
        'offer_title',
        'author',
        'category_id',
        'category_title',
        'min_order',
        'offer_rate'
    ];


}
