<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForumCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('forum_categories')->truncate();
        DB::table('forum_categories')->insert([
            [
                'name'=>'Technology',
                'slug'=>'technology',
            ],
            [
                'name'=>'Food',
                'slug'=>'food',
            ],
            [
                'name'=>'Electronic',
                'slug'=>'electronic'
            ],
            [
                'name'=>'Gadget',
                'slug'=>'gadget',
            ],
            [
                'name'=>'Business',
                'slug'=>'business'
            ],
            [
                'name'=>'Game',
                'slug'=>'game',
            ]
        ]);
    }
}
