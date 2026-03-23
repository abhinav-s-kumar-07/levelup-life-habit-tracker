<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class FrameUnlockService
{
    public static function levelFromXp(int $xp): int
    {
        if ($xp >= 200) return 4;
        if ($xp >= 100) return 3;
        if ($xp >= 50) return 2;
        return 1;
    }

    public static function checkAndUnlock(int $userId): array
    {
        $xp = (int) (DB::table('rewards')->where('user_id', $userId)->value('points') ?? 0);
        $level = self::levelFromXp($xp);
        $bestStreak = self::bestStreakForUser($userId);

        $frames = DB::table('avatar_frames')->orderBy('id')->get();

        $alreadyUnlockedIds = DB::table('user_unlocked_frames')
            ->where('user_id', $userId)
            ->pluck('frame_id')
            ->map(fn ($v) => (int) $v)
            ->toArray();

        $unlockedSet = array_fill_keys($alreadyUnlockedIds, true);
        $newlyUnlockedNames = [];

        foreach ($frames as $frame) {
            $frameId = (int) $frame->id;
            if (isset($unlockedSet[$frameId])) {
                continue;
            }

            $shouldUnlock = false;
            $required = (int) ($frame->requirement_value ?? 0);

            if ($frame->unlock_type === 'xp') {
                $shouldUnlock = $xp >= $required;
            } elseif ($frame->unlock_type === 'level') {
                $shouldUnlock = $level >= $required;
            } elseif ($frame->unlock_type === 'habit_streak') {
                $shouldUnlock = $bestStreak >= $required;
            } elseif ($frame->unlock_type === 'manual') {
                $shouldUnlock = false;
            }

            if ($shouldUnlock) {
                DB::table('user_unlocked_frames')->insert([
                    'user_id' => $userId,
                    'frame_id' => $frameId,
                    'unlocked_at' => now()->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $newlyUnlockedNames[] = (string) $frame->name;
                $unlockedSet[$frameId] = true;
            }
        }

        return $newlyUnlockedNames;
    }

    private static function bestStreakForUser(int $userId): int
    {
        $habitIds = DB::table('habits')
            ->where('user_id', $userId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $best = 0;
        foreach ($habitIds as $habitId) {
            $best = max($best, self::calculateStreak($habitId));
        }

        return $best;
    }

    private static function calculateStreak(int $habitId): int
    {
        $dates = DB::table('habit_logs')
            ->where('habit_id', $habitId)
            ->where('status', 'done')
            ->orderBy('log_date', 'desc')
            ->pluck('log_date')
            ->toArray();

        if (count($dates) === 0) return 0;

        $streak = 0;
        $expected = now()->toDateString();

        foreach ($dates as $d) {
            if ($d == $expected) {
                $streak++;
                $expected = date('Y-m-d', strtotime($expected . ' -1 day'));
            } else {
                break;
            }
        }

        return $streak;
    }
}
