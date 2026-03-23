<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('avatar_frames')) {
            return;
        }

        $updates = [
            'bronze-frame' => ['unlock_type' => 'xp', 'requirement_value' => 40],
            'silver-frame' => ['unlock_type' => 'xp', 'requirement_value' => 90],
            'gold-frame' => ['unlock_type' => 'xp', 'requirement_value' => 150],
            'streak-flame-frame' => ['unlock_type' => 'xp', 'requirement_value' => 210],
            'diamond-frame' => ['unlock_type' => 'xp', 'requirement_value' => 280],
        ];

        foreach ($updates as $slug => $rule) {
            DB::table('avatar_frames')
                ->where('slug', $slug)
                ->update([
                    'unlock_type' => $rule['unlock_type'],
                    'requirement_value' => $rule['requirement_value'],
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('avatar_frames')) {
            return;
        }

        $revert = [
            'bronze-frame' => ['unlock_type' => 'level', 'requirement_value' => 2],
            'silver-frame' => ['unlock_type' => 'level', 'requirement_value' => 3],
            'gold-frame' => ['unlock_type' => 'level', 'requirement_value' => 4],
            'streak-flame-frame' => ['unlock_type' => 'habit_streak', 'requirement_value' => 7],
            'diamond-frame' => ['unlock_type' => 'xp', 'requirement_value' => 200],
        ];

        foreach ($revert as $slug => $rule) {
            DB::table('avatar_frames')
                ->where('slug', $slug)
                ->update([
                    'unlock_type' => $rule['unlock_type'],
                    'requirement_value' => $rule['requirement_value'],
                    'updated_at' => now(),
                ]);
        }
    }
};

