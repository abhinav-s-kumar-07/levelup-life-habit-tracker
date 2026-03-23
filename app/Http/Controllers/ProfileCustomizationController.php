<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FrameUnlockService;

class ProfileCustomizationController extends Controller
{
    public function show()
    {
        if (!session('user_id')) return redirect('/login');

        $userId = (int) session('user_id');

        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user) {
            session()->forget('user_id');
            return redirect('/login');
        }

        FrameUnlockService::checkAndUnlock($userId);

        $xp = (int) (DB::table('rewards')->where('user_id', $userId)->value('points') ?? 0);
        $level = FrameUnlockService::levelFromXp($xp);
        $bestStreak = $this->bestStreakForUser($userId);

        $avatars = $this->allowedAvatars();
        $avatarNames = $this->avatarNames();
        if (!in_array((string) $user->avatar, $avatars, true)) {
            $user->avatar = 'avatar1.png';
        }

        $unlockedIds = DB::table('user_unlocked_frames')
            ->where('user_id', $userId)
            ->pluck('frame_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();
        $unlockedSet = array_fill_keys($unlockedIds, true);

        $frames = DB::table('avatar_frames')
            ->orderBy('id')
            ->get()
            ->map(function ($frame) use ($unlockedSet, $user) {
                $frame->is_unlocked = isset($unlockedSet[(int) $frame->id]);
                $frame->is_equipped = ((int) ($user->equipped_frame_id ?? 0) === (int) $frame->id);
                return $frame;
            });

        return view('profile.customize', [
            'user' => $user,
            'xp' => $xp,
            'level' => $level,
            'bestStreak' => $bestStreak,
            'avatars' => $avatars,
            'avatarNames' => $avatarNames,
            'frames' => $frames,
        ]);
    }

    public function updateAvatar(Request $request)
    {
        if (!session('user_id')) return redirect('/login');

        $allowed = $this->allowedAvatars();

        $request->validate([
            'avatar' => 'required|string|in:' . implode(',', $allowed),
        ]);

        DB::table('users')
            ->where('id', (int) session('user_id'))
            ->update([
                'avatar' => $request->avatar,
                'updated_at' => now(),
            ]);

        return redirect('/profile/customize')->with('success', 'Avatar updated.');
    }

    public function equipFrame(Request $request)
    {
        if (!session('user_id')) return redirect('/login');

        $request->validate([
            'frame_id' => 'nullable|integer',
        ]);

        $userId = (int) session('user_id');
        $frameId = $request->frame_id !== null ? (int) $request->frame_id : null;

        if ($frameId === null) {
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'equipped_frame_id' => null,
                    'updated_at' => now(),
                ]);

            return redirect('/profile/customize')->with('success', 'Frame removed.');
        }

        $frameExists = DB::table('avatar_frames')->where('id', $frameId)->exists();
        if (!$frameExists) {
            return redirect('/profile/customize')->with('error', 'Frame not found.');
        }

        $isUnlocked = DB::table('user_unlocked_frames')
            ->where('user_id', $userId)
            ->where('frame_id', $frameId)
            ->exists();

        if (!$isUnlocked) {
            return redirect('/profile/customize')->with('error', 'Frame is locked.');
        }

        DB::table('users')
            ->where('id', $userId)
            ->update([
                'equipped_frame_id' => $frameId,
                'updated_at' => now(),
            ]);

        return redirect('/profile/customize')->with('success', 'Frame equipped.');
    }

    private function allowedAvatars(): array
    {
        $dir = public_path('avatars');
        $avatars = [];

        if (is_dir($dir)) {
            $files = scandir($dir) ?: [];
            foreach ($files as $f) {
                if (preg_match('/\.png$/i', $f)) {
                    $avatars[] = $f;
                }
            }
        }

        if (count($avatars) > 0) {
            sort($avatars);
            return array_values(array_unique($avatars));
        }

        $fallback = [];
        for ($i = 1; $i <= 10; $i++) {
            $fallback[] = "avatar{$i}.png";
        }
        return $fallback;
    }

    private function avatarNames(): array
    {
        return [
            'avatar1.png' => 'Atlas',
            'avatar2.png' => 'Nova',
            'avatar3.png' => 'Sage',
            'avatar4.png' => 'Orion',
            'avatar5.png' => 'Iris',
            'avatar6.png' => 'Kai',
            'avatar7.png' => 'Rune',
            'avatar8.png' => 'Vale',
            'avatar9.png' => 'Ember',
            'avatar10.png' => 'Astra',
        ];
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
