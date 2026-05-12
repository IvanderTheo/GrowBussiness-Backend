<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('product_categories')->insert([
            [
                'name'=>'makanan & minuman',
                'description'=>'test',
            ],
            [
                'name'=>'elektronik & gadget',
                'description'=>'test',
            ],
        ]);
    }
}
