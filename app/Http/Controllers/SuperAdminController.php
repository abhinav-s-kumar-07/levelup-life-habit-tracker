<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function index()
    {
        if (!session('user_id')) return redirect('/login');

        $me = DB::table('users')->where('id', (int) session('user_id'))->first();
        if (!$me || !(bool) ($me->is_super_admin ?? false)) {
            return redirect('/dashboard')->with('error', 'Admin access required.');
        }
        session(['is_super_admin' => true]);

        $usersCount = DB::table('users')->count();
        $habitsCount = DB::table('habits')->count();
        $logsCount = DB::table('habit_logs')->count();
        $friendsCount = DB::table('friendships')->count();
        $unlockedFramesCount = DB::table('user_unlocked_frames')->count();
        $equippedFramesCount = DB::table('users')->whereNotNull('equipped_frame_id')->count();
        $framesCount = DB::table('avatar_frames')->count();

        $topUsers = DB::table('users')
            ->leftJoin('rewards', 'rewards.user_id', '=', 'users.id')
            ->leftJoin('avatar_frames', 'avatar_frames.id', '=', 'users.equipped_frame_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.equipped_frame_id',
                'avatar_frames.name as equipped_frame_name',
                DB::raw('COALESCE(rewards.points, 0) as points')
            )
            ->orderByDesc('points')
            ->limit(30)
            ->get();

        $frames = DB::table('avatar_frames')->orderBy('id')->get();

        $recentActivities = DB::table('activity_logs')
            ->leftJoin('users', 'users.id', '=', 'activity_logs.actor_user_id')
            ->select('activity_logs.*', 'users.name as actor_name')
            ->orderByDesc('activity_logs.created_at')
            ->limit(25)
            ->get();

        return view('admin.index', compact(
            'usersCount',
            'habitsCount',
            'logsCount',
            'friendsCount',
            'unlockedFramesCount',
            'equippedFramesCount',
            'framesCount',
            'topUsers',
            'frames',
            'recentActivities'
        ));
    }

    public function frames()
    {
        if ($resp = $this->requireAdmin()) return $resp;

        $frames = DB::table('avatar_frames')
            ->orderBy('id')
            ->get()
            ->map(function ($f) {
                $f->unlocked_count = DB::table('user_unlocked_frames')->where('frame_id', $f->id)->count();
                $f->equipped_count = DB::table('users')->where('equipped_frame_id', $f->id)->count();
                return $f;
            });

        return view('admin.frames', compact('frames'));
    }

    public function updateFrame(Request $request, $id)
    {
        if ($resp = $this->requireAdmin()) return $resp;

        $frameId = (int) $id;
        $frame = DB::table('avatar_frames')->where('id', $frameId)->first();
        if (!$frame) {
            return redirect('/admin/frames')->with('error', 'Frame not found.');
        }

        $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'required|string|max:120',
            'type' => 'required|in:png,css',
            'asset' => 'nullable|string|max:120',
            'unlock_type' => 'required|in:xp,habit_streak,level,manual',
            'requirement_value' => 'nullable|integer|min:0',
        ]);

        $slugExists = DB::table('avatar_frames')
            ->where('slug', $request->slug)
            ->where('id', '!=', $frameId)
            ->exists();
        if ($slugExists) {
            return redirect('/admin/frames')->with('error', 'Slug already in use.');
        }

        $reqVal = $request->unlock_type === 'manual'
            ? null
            : ($request->requirement_value !== null ? (int) $request->requirement_value : 0);

        DB::table('avatar_frames')
            ->where('id', $frameId)
            ->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'type' => $request->type,
                'asset' => $request->asset,
                'unlock_type' => $request->unlock_type,
                'requirement_value' => $reqVal,
                'updated_at' => now(),
            ]);

        return redirect('/admin/frames')->with('success', 'Frame updated.');
    }

    public function toggleManual($id)
    {
        if ($resp = $this->requireAdmin()) return $resp;

        $frameId = (int) $id;
        $frame = DB::table('avatar_frames')->where('id', $frameId)->first();
        if (!$frame) {
            return redirect('/admin/frames')->with('error', 'Frame not found.');
        }

        if ($frame->unlock_type === 'manual') {
            DB::table('avatar_frames')->where('id', $frameId)->update([
                'unlock_type' => 'level',
                'requirement_value' => 2,
                'updated_at' => now(),
            ]);
            return redirect('/admin/frames')->with('success', 'Frame switched to auto unlock (Level 2).');
        }

        DB::table('avatar_frames')->where('id', $frameId)->update([
            'unlock_type' => 'manual',
            'requirement_value' => null,
            'updated_at' => now(),
        ]);
        return redirect('/admin/frames')->with('success', 'Frame switched to manual unlock.');
    }

    public function unlockFrameForUser(Request $request)
    {
        if ($resp = $this->requireAdmin()) return $resp;

        $request->validate([
            'user_id' => 'required|integer',
            'frame_id' => 'required|integer',
        ]);

        $userId = (int) $request->user_id;
        $frameId = (int) $request->frame_id;

        $userExists = DB::table('users')->where('id', $userId)->exists();
        $frameExists = DB::table('avatar_frames')->where('id', $frameId)->exists();
        if (!$userExists || !$frameExists) {
            return redirect('/admin')->with('error', 'Invalid user or frame.');
        }

        $already = DB::table('user_unlocked_frames')
            ->where('user_id', $userId)
            ->where('frame_id', $frameId)
            ->exists();

        if (!$already) {
            DB::table('user_unlocked_frames')->insert([
                'user_id' => $userId,
                'frame_id' => $frameId,
                'unlocked_at' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect('/admin')->with('success', 'Frame unlocked for user.');
    }

    public function equipFrameForUser(Request $request)
    {
        if ($resp = $this->requireAdmin()) return $resp;

        $request->validate([
            'user_id' => 'required|integer',
            'frame_id' => 'nullable|integer',
        ]);

        $userId = (int) $request->user_id;
        $frameId = $request->frame_id !== null ? (int) $request->frame_id : null;

        $userExists = DB::table('users')->where('id', $userId)->exists();
        if (!$userExists) {
            return redirect('/admin')->with('error', 'Invalid user.');
        }

        if ($frameId === null) {
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'equipped_frame_id' => null,
                    'updated_at' => now(),
                ]);
            return redirect('/admin')->with('success', 'Frame removed for user.');
        }

        $frameExists = DB::table('avatar_frames')->where('id', $frameId)->exists();
        if (!$frameExists) {
            return redirect('/admin')->with('error', 'Invalid frame.');
        }

        $isUnlocked = DB::table('user_unlocked_frames')
            ->where('user_id', $userId)
            ->where('frame_id', $frameId)
            ->exists();

        if (!$isUnlocked) {
            DB::table('user_unlocked_frames')->insert([
                'user_id' => $userId,
                'frame_id' => $frameId,
                'unlocked_at' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('users')
            ->where('id', $userId)
            ->update([
                'equipped_frame_id' => $frameId,
                'updated_at' => now(),
            ]);

        return redirect('/admin')->with('success', 'Frame equipped for user.');
    }

    private function requireAdmin()
    {
        if (!session('user_id')) return redirect('/login');

        $me = DB::table('users')->where('id', (int) session('user_id'))->first();
        if (!$me || !(bool) ($me->is_super_admin ?? false)) {
            return redirect('/dashboard')->with('error', 'Admin access required.');
        }
        session(['is_super_admin' => true]);
        return null;
    }
}
