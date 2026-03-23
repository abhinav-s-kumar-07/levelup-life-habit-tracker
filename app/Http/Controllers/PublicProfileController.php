<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PublicProfileController extends Controller
{
    public function show($id)
    {
        $userId = (int) $id;

        $user = DB::table('users')
            ->leftJoin('avatar_frames', 'avatar_frames.id', '=', 'users.equipped_frame_id')
            ->where('users.id', $userId)
            ->select(
                'users.id',
                'users.name',
                'users.avatar',
                'users.equipped_frame_id',
                'avatar_frames.name as frame_name',
                'avatar_frames.type as frame_type',
                'avatar_frames.asset as frame_asset'
            )
            ->first();

        if (!$user) {
            abort(404);
        }

        $xp = (int) (DB::table('rewards')->where('user_id', $userId)->value('points') ?? 0);
        $level = \App\Services\FrameUnlockService::levelFromXp($xp);
        $bestStreak = $this->bestStreakForUser($userId);

        if (!$user->avatar) {
            $user->avatar = 'avatar1.png';
        }

        return view('public_profile.show', [
            'user' => $user,
            'xp' => $xp,
            'level' => $level,
            'bestStreak' => $bestStreak,
        ]);
    }

    private function bestStreakForUser(int $userId): int
    {
        $habitIds = DB::table('habits')
            ->where('user_id', $userId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $best = 0;
        foreach ($habitIds as $habitId) {
            $best = max($best, $this->calculateStreak($habitId));
        }

        return $best;
    }

    private function calculateStreak(int $habitId): int
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
