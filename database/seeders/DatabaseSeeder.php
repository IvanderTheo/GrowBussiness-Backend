<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('product_categories')->truncate();
        DB::table('forum_categories')->truncate();
        DB::table('users')->truncate();

        $this->call([
            UserSeeder::class,
            ForumCategorySeeder::class,
            ProductCategorySeeder::class,
        ]);
    }
}
