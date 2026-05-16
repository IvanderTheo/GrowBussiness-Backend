<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('forums')->truncate();
        $userId = DB::table('users')->value('id') ?? Str::uuid()->toString();
        $data = [];
        for($i = 0;$i<10;$i++) {
            $category_id = rand(1,4);
            $view = rand(10000,100000);
            $title = "";
            if($category_id===1) {
                $title = "title tech";
            } else {
                $title = "title food";
            }
            $input = [
                'user_id'=>$userId,
                'category_id'=>$category_id,
                'title'=>"$title {$i}",
                'content'=>"content {$i}",
                'views'=>$view,
                'created_at'=>now(),
            ];
            array_push($data,$input);
        }
        DB::table('forums')->insert($data);
    }
}
