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
                'category_title' => 'Roman'
            ],[
                'category_title' => 'Kişisel Gelişim'
            ],[
                'category_title' => 'Bilim'
            ],[
                'category_title' => 'Din Tasavvuf'
            ],[
                'category_title' => 'Çocuk ve Gençlik'
            ],[
                'category_title' => 'Öykü'
            ],[
                'category_title' => 'Felsefe'
            ]
        ]);
    }
}
