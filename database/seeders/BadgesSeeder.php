<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BadgesSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('badges')) return;

        $badges = [
            ['name' => 'First Habit', 'description' => 'Completed first habit', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Weekly Streak', 'description' => 'Completed all habits in a week', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Monthly Streak', 'description' => 'Completed all habits in a month', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($badges as $badge) {
            $exists = DB::table('badges')->where('name', $badge['name'])->exists();
            if (!$exists) {
                DB::table('badges')->insert($badge);
            }
        }
    }
}