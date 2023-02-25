<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offers')->insert([
            [
                'offer_id' => '1',
                'offer_title' => 'getTwoPayOne',
                'author' => 'Sabahattin Ali',
                'category_id' => 1,
                'category_title' => 'Roman',
                'min_order' => null,
                'offer_rate' => null
            ],[
                'offer_id' => '2',
                'offer_title' => 'discountRate',
                'author' => null,
                'category_id' => null,
                'category_title' => null,
                'min_order' => 100,
                'offer_rate' => 5
            ]
        ]);
    }
}
