<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

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
