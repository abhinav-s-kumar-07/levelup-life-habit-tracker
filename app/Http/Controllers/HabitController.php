<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FrameUnlockService;

class HabitController extends Controller
{
    private function frequencyOptions(): array
    {
        return ['Daily', 'Weekdays', 'Weekends', '3 times a week', '4 times a week', '5 times a week'];
    }

    private function difficultyOptions(): array
    {
        return ['Easy', 'Medium', 'Hard'];
    }

    /* -----------------------------
       XP → Level
    ----------------------------- */
    private function levelFromXp(int $xp): string
    {
        if ($xp >= 200) return "Level 4 — Master";
        if ($xp >= 100) return "Level 3 — Challenger";
        if ($xp >= 50)  return "Level 2 — Explorer";
        return "Level 1 — Beginner";
    }

    /* -----------------------------
       Streak calculator (per habit)
    ----------------------------- */
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

    /* -----------------------------
       Award badge helper
    ----------------------------- */
    private function awardBadge(int $userId, int $badgeId): void
    {
        $already = DB::table('user_badges')
            ->where('user_id', $userId)
            ->where('badge_id', $badgeId)
            ->exists();

        if (!$already) {
            DB::table('user_badges')->insert([
                'user_id'   => $userId,
                'badge_id'  => $badgeId,
                'earned_at' => now()->toDateString(),
            ]);
        }
    }

    /* -----------------------------
       Activity log helper (safe)
    ----------------------------- */
    private function logActivity(int $actorUserId, string $type, string $title, ?string $subtitle = null, ?string $icon = null): void
    {
        // Safety: if table doesn't exist yet, don't break the app
        try {
            DB::table('activity_logs')->insert([
                'actor_user_id' => $actorUserId,
                'type' => $type,
                'title' => $title,
                'subtitle' => $subtitle,
                'icon' => $icon,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // do nothing (avoid breaking core habit tracking)
        }
    }

    /* -----------------------------
       Dashboard
    ----------------------------- */
 public function dashboard(Request $request)
{
    if (!session('user_id')) return redirect('/login');

    $userId = (int) session('user_id');
    $today  = now()->toDateString();
    $weekStart = now()->startOfWeek()->toDateString();
    $weekEnd   = now()->endOfWeek()->toDateString();

    // ✅ Fetch habits for this user
    $habits = DB::table('habits')
        ->select('id', 'title as habit_name', 'frequency', 'difficulty')
        ->where('user_id', $userId)
        ->orderBy('id', 'desc')
        ->get();

    // ✅ User points
    $points = (int) (DB::table('rewards')->where('user_id', $userId)->value('points') ?? 0);

    // ✅ Frame unlock check
    FrameUnlockService::checkAndUnlock($userId);

    // ✅ Level based on XP
    $level = $this->levelFromXp($points);

    // ✅ Profile + equipped frame
    $profile = DB::table('users')
        ->leftJoin('avatar_frames', 'avatar_frames.id', '=', 'users.equipped_frame_id')
        ->where('users.id', $userId)
        ->select(
            'users.id',
            'users.name',
            'users.avatar',
            'users.is_super_admin',
            'users.equipped_frame_id',
            'avatar_frames.type as frame_type',
            'avatar_frames.asset as frame_asset',
            'avatar_frames.name as frame_name'
        )
        ->first();

    session(['is_super_admin' => (bool) ($profile->is_super_admin ?? false)]);

    // ✅ Completed today
    $completedToday = DB::table('habit_logs')
        ->join('habits', 'habit_logs.habit_id', '=', 'habits.id')
        ->where('habits.user_id', $userId)
        ->where('habit_logs.log_date', $today)
        ->pluck('habit_logs.habit_id')
        ->toArray();

    // ✅ Weekly completed
    $completedThisWeek = DB::table('habit_logs')
        ->join('habits', 'habit_logs.habit_id', '=', 'habits.id')
        ->where('habits.user_id', $userId)
        ->whereBetween('habit_logs.log_date', [$weekStart, $weekEnd])
        ->where('habit_logs.status', 'done')
        ->count();

    $xpThisWeek = $completedThisWeek * 10;

    // ✅ Calculate streaks
    $streaks = [];
    foreach ($habits as $h) {
        $streaks[$h->id] = $this->calculateStreak((int)$h->id);
    }

    // ✅ Tab filter
    $tab = $request->query('tab', 'all'); // all | done | pending

    $filteredHabits = $habits->filter(function ($h) use ($tab, $completedToday) {
        $done = in_array($h->id, $completedToday);

        if ($tab === 'done') return $done;
        if ($tab === 'pending') return !$done;
        return true;
    })->values();

    // ✅ Return view
    return view('dashboard', [
        'habits' => $filteredHabits,
        'points' => $points,
        'level' => $level,
        'completedToday' => $completedToday,
        'streaks' => $streaks,
        'tab' => $tab,
        'completedThisWeek' => $completedThisWeek,
        'xpThisWeek' => $xpThisWeek,
        'weekStart' => $weekStart,
        'weekEnd' => $weekEnd,
        'profile' => $profile,
    ]);
}
    /* -----------------------------
       Add habit
    ----------------------------- */
    public function create()
    {
        if (!session('user_id')) return redirect('/login');
        return view('add_habit', [
            'frequencyOptions' => $this->frequencyOptions(),
            'difficultyOptions' => $this->difficultyOptions(),
        ]);
    }

    public function store(Request $request)
    {
        if (!session('user_id')) return redirect('/login');

        $request->validate([
            'habit_name' => 'required|string|max:100',
            'frequency'  => 'required|string|in:' . implode(',', $this->frequencyOptions()),
            'difficulty' => 'required|string|in:' . implode(',', $this->difficultyOptions()),
        ]);

        DB::table('habits')->insert([
            'user_id'    => (int) session('user_id'),
            'title' => $request->habit_name,
            'description' => null,
            'frequency'  => $request->frequency,
            'difficulty' => $request->difficulty,
            'xp_reward' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Optional: log activity for adding habit
        $this->logActivity(
            (int) session('user_id'),
            'habit_added',
            'Created a new habit',
            '📌 ' . $request->habit_name,
            '📌'
        );

        return redirect('/dashboard')->with('success', 'Habit added!');
    }

    /* -----------------------------
       Complete habit (award XP + badges)
    ----------------------------- */
    public function complete($id)
    {
        if (!session('user_id')) return redirect('/login');

        $userId = (int) session('user_id');
        $today  = now()->toDateString();

        // Ensure habit belongs to this user
        $habit = DB::table('habits')
            ->select('id', 'title as habit_name', 'user_id')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$habit) {
            return redirect('/dashboard')->with('error', 'Habit not found.');
        }

        // Prevent double completion today
        $alreadyDone = DB::table('habit_logs')
            ->where('habit_id', $id)
            ->where('log_date', $today)
            ->exists();

        if ($alreadyDone) {
            return redirect('/dashboard')->with('error', 'Already completed today!');
        }

        // Insert habit log (NO created_at/updated_at unless your table has them)
        DB::table('habit_logs')->insert([
            'habit_id' => (int) $id,
            'log_date' => $today,
            'status'   => 'done',
        ]);

        // Award XP
        DB::table('rewards')
            ->where('user_id', $userId)
            ->increment('points', 10);

        // Recalculate streak AFTER inserting log
        $streak = $this->calculateStreak((int)$id);

        // ✅ Add to Friends Activity Feed
        $this->logActivity(
            $userId,
            'habit_done',
            'Completed: ' . ($habit->habit_name ?? 'Habit'),
            '🔥 Streak ' . $streak . ' • +10 XP',
            '🔥'
        );

        /* -----------------------------
           🏅 BADGE LOGIC
        ----------------------------- */

        // Total completions by this user (all habits)
        $totalCompletions = DB::table('habit_logs')
            ->join('habits', 'habit_logs.habit_id', '=', 'habits.id')
            ->where('habits.user_id', $userId)
            ->count();

        // Current XP
        $xp = (int) (DB::table('rewards')->where('user_id', $userId)->value('points') ?? 0);

        // Badge rules (badge IDs must match your badges table)
        if ($totalCompletions >= 1)  $this->awardBadge($userId, 1); // First Step
        if ($streak >= 3)            $this->awardBadge($userId, 2); // 3-Day Streak
        if ($streak >= 7)            $this->awardBadge($userId, 3); // 7-Day Streak
        if ($xp >= 50)               $this->awardBadge($userId, 4); // 50 XP
        if ($xp >= 100)              $this->awardBadge($userId, 5); // 100 XP

        $newFrames = FrameUnlockService::checkAndUnlock($userId);

        $successMessage = '+10 XP! Habit completed.';
        if (!empty($newFrames)) {
            $successMessage .= ' New frame unlocked: ' . implode(', ', $newFrames);
        }

        return redirect('/dashboard')->with('success', $successMessage);
    }

    /* -----------------------------
       Delete habit
    ----------------------------- */
    public function delete($id)
    {
        if (!session('user_id')) return redirect('/login');

        $userId = (int) session('user_id');

        $habit = DB::table('habits')
            ->select('id', 'title as habit_name', 'user_id')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$habit) {
            return redirect('/dashboard')->with('error', 'Habit not found.');
        }

        DB::table('habit_logs')->where('habit_id', $id)->delete();
        DB::table('habits')->where('id', $id)->delete();

        // Optional: log delete
        $this->logActivity(
            $userId,
            'habit_deleted',
            'Deleted a habit',
            '🗑 ' . ($habit->habit_name ?? 'Habit'),
            '🗑'
        );

        return redirect('/dashboard')->with('success', 'Habit deleted.');
    }

    /* -----------------------------
       Edit habit
    ----------------------------- */
    public function edit($id)
    {
        if (!session('user_id')) return redirect('/login');

        $habit = DB::table('habits')
            ->select('id', 'title as habit_name', 'frequency', 'difficulty')
            ->where('id', $id)
            ->where('user_id', (int) session('user_id'))
            ->first();

        if (!$habit) {
            return redirect('/dashboard')->with('error', 'Habit not found.');
        }

        return view('edit_habit', [
            'habit' => $habit,
            'frequencyOptions' => $this->frequencyOptions(),
            'difficultyOptions' => $this->difficultyOptions(),
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!session('user_id')) return redirect('/login');

        $request->validate([
            'habit_name' => 'required|string|max:100',
            'frequency'  => 'required|string|in:' . implode(',', $this->frequencyOptions()),
            'difficulty' => 'required|string|in:' . implode(',', $this->difficultyOptions()),
        ]);

        $habit = DB::table('habits')
            ->where('id', $id)
            ->where('user_id', (int) session('user_id'))
            ->first();

        if (!$habit) {
            return redirect('/dashboard')->with('error', 'Habit not found.');
        }

        DB::table('habits')
            ->where('id', $id)
            ->update([
                'title' => $request->habit_name,
                'frequency'  => $request->frequency,
                'difficulty' => $request->difficulty,
                'updated_at' => now(),
            ]);

        // Optional: log edit
        $this->logActivity(
            (int) session('user_id'),
            'habit_updated',
            'Updated a habit',
            '✏️ ' . $request->habit_name,
            '✏️'
        );

        return redirect('/dashboard')->with('success', 'Habit updated successfully.');
    }
}
