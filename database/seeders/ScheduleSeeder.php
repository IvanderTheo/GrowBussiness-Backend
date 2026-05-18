<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Schedules;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('schedules')->truncate();
        $userId = DB::table('users')->value('id') ?? Str::uuid()->toString();
        //pending
        Schedules::create([
            'user_id'=>$userId,
            'title'=>'ini judul pending',
            'description'=>'ini deskripsi schedule',
            'start_datetime'=>'2026-05-20 00:00:00',
            'end_datetime'=>'2026-05-21 00:00:00',
            'status'=>'pending'
        ]);
        //ongoing
        Schedules::create([
            'user_id'=>$userId,
            'title'=>'ini judul ongoing',
            'description'=>'ini deskripsi schedule',
            'start_datetime'=>'2026-05-16 00:00:00',
            'end_datetime'=>'2026-05-17 00:00:00',
            'status'=>'ongoing',
        ]);
        //completed
        Schedules::create([
            'user_id'=>$userId,
            'title'=>'ini judul completed',
            'description'=>'ini deskripsi schedule',
            'start_datetime'=>'2026-05-13 00:00:00',
            'end_datetime'=>'2026-05-14 00:00:00',
            'status'=>'completed'
        ]);
    }
}
