<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
            'categoryName' => 'Roman'
        ],[
            'categoryName' => 'Kişisel Gelişim'
        ],[
            'categoryName' => 'Bilim'
        ],[
            'categoryName' => 'Din Tasavvuf'
        ],[
            'categoryName' => 'Çocuk ve Gençlik'
        ],[
            'categoryName' => 'Öykü'
        ],[
            'categoryName' => 'Felsefe'
            ]
        ]);

    }
}
