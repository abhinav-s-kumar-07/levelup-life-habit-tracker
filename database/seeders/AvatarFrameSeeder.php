<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvatarFrameSeeder extends Seeder
{
    public function run(): void
    {
        $frames = [
            [
                'name' => 'Bronze Frame',
                'slug' => 'bronze-frame',
                'type' => 'png',
                'asset' => 'frame_bronze.png',
                'unlock_type' => 'xp',
                'requirement_value' => 40,
            ],
            [
                'name' => 'Silver Frame',
                'slug' => 'silver-frame',
                'type' => 'png',
                'asset' => 'frame_silver.png',
                'unlock_type' => 'xp',
                'requirement_value' => 90,
            ],
            [
                'name' => 'Gold Frame',
                'slug' => 'gold-frame',
                'type' => 'png',
                'asset' => 'frame_gold.png',
                'unlock_type' => 'xp',
                'requirement_value' => 150,
            ],
            [
                'name' => 'Streak Flame Frame',
                'slug' => 'streak-flame-frame',
                'type' => 'css',
                'asset' => 'flame',
                'unlock_type' => 'xp',
                'requirement_value' => 210,
            ],
            [
                'name' => 'Diamond Frame',
                'slug' => 'diamond-frame',
                'type' => 'png',
                'asset' => 'frame_diamond.png',
                'unlock_type' => 'xp',
                'requirement_value' => 280,
            ],
            [
                'name' => 'Special Frame',
                'slug' => 'special-frame',
                'type' => 'css',
                'asset' => 'special',
                'unlock_type' => 'manual',
                'requirement_value' => null,
            ],
        ];

        foreach ($frames as $frame) {
            $existing = DB::table('avatar_frames')->where('slug', $frame['slug'])->first();

            if ($existing) {
                DB::table('avatar_frames')
                    ->where('id', $existing->id)
                    ->update([
                        'name' => $frame['name'],
                        'type' => $frame['type'],
                        'asset' => $frame['asset'],
                        'unlock_type' => $frame['unlock_type'],
                        'requirement_value' => $frame['requirement_value'],
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('avatar_frames')->insert([
                    'name' => $frame['name'],
                    'slug' => $frame['slug'],
                    'type' => $frame['type'],
                    'asset' => $frame['asset'],
                    'unlock_type' => $frame['unlock_type'],
                    'requirement_value' => $frame['requirement_value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
