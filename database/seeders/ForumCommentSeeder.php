<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForumCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('forum_comments')->truncate();
        $userId = Str::uuid()->toString();
        DB::table('users')->insert([
            [
                'id'=>$userId,
                'first_name'=>'test',
                'last_name'=>'test',
                'email'=>'test2@gmail.com',
                'role'=>'user',
                'password'=>'test123456789',
                'is_agree_terms'=>true,
            ]
        ]);
        $data = [];
        for($i= 0;$i<=100;$i++) {
            $forum_id=rand(1,5);
            $comment = "";
            if($forum_id===1) {
                $comment = "ini adalah comment tech $i";
            } else {
                $comment = "ini adalah comment biasa $i";
            }
            $input = [
                'user_id' => $userId,
                'forum_id' => $forum_id,
                'comment' => $comment,
                'created_at' => now(),
            ];
            array_push($data, $input);
        }
        DB::table('forum_comments')->insert($data);
    }
}
