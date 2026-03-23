<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    private function requireLogin()
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please login first.');
        }
        return null;
    }

    // Safe activity insert (won't break app if table missing)
    private function logActivity(int $actorUserId, string $type, string $title, ?string $subtitle = null, ?string $icon = null): void
    {
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
            // ignore
        }
    }

    // FRIENDS HOME: list incoming/outgoing requests + accepted friends
    public function index()
    {
        if ($r = $this->requireLogin()) return $r;

        $uid = (int) session('user_id');

        // Incoming requests (someone -> me)
        $incoming = DB::table('friendships')
            ->join('users', 'users.id', '=', 'friendships.requester_id')
            ->where('friendships.addressee_id', $uid)
            ->where('friendships.status', 'pending')
            ->select(
                'friendships.id as friendship_id',
                'users.id as user_id',
                'users.name',
                'users.email',
                'friendships.created_at'
            )
            ->orderByDesc('friendships.created_at')
            ->get();

        // Outgoing requests (me -> someone)
        $outgoing = DB::table('friendships')
            ->join('users', 'users.id', '=', 'friendships.addressee_id')
            ->where('friendships.requester_id', $uid)
            ->where('friendships.status', 'pending')
            ->select(
                'friendships.id as friendship_id',
                'users.id as user_id',
                'users.name',
                'users.email',
                'friendships.created_at'
            )
            ->orderByDesc('friendships.created_at')
            ->get();

        // Accepted friends (either direction)
        $accepted = DB::table('friendships')
            ->where('status', 'accepted')
            ->where(function ($q) use ($uid) {
                $q->where('requester_id', $uid)->orWhere('addressee_id', $uid);
            })
            ->orderByDesc('updated_at')
            ->get();

        $friends = $accepted->map(function ($row) use ($uid) {
            $friendId = ((int)$row->requester_id === $uid) ? (int)$row->addressee_id : (int)$row->requester_id;
            $u = DB::table('users')->where('id', $friendId)->first();

            return (object)[
                'friendship_id' => $row->id,
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
            ];
        });

        return view('friends.index', compact('incoming', 'outgoing', 'friends'));
    }

    // SEARCH USERS BY NAME/EMAIL
    public function search(Request $request)
    {
        if ($r = $this->requireLogin()) return $r;

        $uid = (int) session('user_id');
        $q = trim($request->query('q', ''));

        if ($q === '') {
            return redirect('/friends')->with('error', 'Type a name or email to search.');
        }

        $results = DB::table('users')
            ->where('id', '!=', $uid)
            ->where(function ($query) use ($q) {
                $query->where('email', 'like', "%$q%")
                    ->orWhere('name', 'like', "%$q%");
            })
            ->limit(15)
            ->get()
            ->map(function ($u) use ($uid) {
                $existing = DB::table('friendships')
                    ->where(function ($q) use ($uid, $u) {
                        $q->where('requester_id', $uid)->where('addressee_id', $u->id);
                    })
                    ->orWhere(function ($q) use ($uid, $u) {
                        $q->where('requester_id', $u->id)->where('addressee_id', $uid);
                    })
                    ->first();

                $u->relation = $existing ? $existing->status : 'none';
                $u->relation_id = $existing ? $existing->id : null;

                return $u;
            });

        return view('friends.search', compact('results', 'q'));
    }

    // SEND REQUEST (me -> userId)
    public function sendRequest($userId)
    {
        if ($r = $this->requireLogin()) return $r;

        $uid = (int) session('user_id');
        $userId = (int) $userId;

        if ($userId === $uid) {
            return redirect('/friends')->with('error', 'You cannot add yourself.');
        }

        $exists = DB::table('friendships')
            ->where(function ($q) use ($uid, $userId) {
                $q->where('requester_id', $uid)->where('addressee_id', $userId);
            })
            ->orWhere(function ($q) use ($uid, $userId) {
                $q->where('requester_id', $userId)->where('addressee_id', $uid);
            })
            ->first();

        if ($exists) {
            return redirect('/friends')->with('error', 'Friend request already exists.');
        }

        DB::table('friendships')->insert([
            'requester_id' => $uid,
            'addressee_id' => $userId,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Activity
        $this->logActivity(
            $uid,
            'friend_request_sent',
            'Sent a friend request',
            '📨 Waiting for acceptance',
            '📨'
        );

        return redirect('/friends')->with('success', 'Friend request sent.');
    }

    // ACCEPT REQUEST (only addressee can accept)
    public function accept($friendshipId)
    {
        if ($r = $this->requireLogin()) return $r;

        $uid = (int) session('user_id');
        $friendshipId = (int) $friendshipId;

        $row = DB::table('friendships')->where('id', $friendshipId)->first();
        if (!$row || (int)$row->addressee_id !== $uid || $row->status !== 'pending') {
            return redirect('/friends')->with('error', 'Invalid request.');
        }

        DB::table('friendships')->where('id', $friendshipId)->update([
            'status' => 'accepted',
            'updated_at' => now(),
        ]);

        // Activity (IMPORTANT: before return)
        $this->logActivity(
            $uid,
            'friend_accepted',
            'Accepted a friend request',
            '🤝 New friend added',
            '🤝'
        );

        return redirect('/friends')->with('success', 'Friend request accepted.');
    }

    // REJECT REQUEST (only addressee can reject) -> delete row
    public function reject($friendshipId)
    {
        if ($r = $this->requireLogin()) return $r;

        $uid = (int) session('user_id');
        $friendshipId = (int) $friendshipId;

        $row = DB::table('friendships')->where('id', $friendshipId)->first();
        if (!$row || (int)$row->addressee_id !== $uid || $row->status !== 'pending') {
            return redirect('/friends')->with('error', 'Invalid request.');
        }

        DB::table('friendships')->where('id', $friendshipId)->delete();

        // Activity
        $this->logActivity(
            $uid,
            'friend_rejected',
            'Rejected a friend request',
            '🚫 Request declined',
            '🚫'
        );

        return redirect('/friends')->with('success', 'Friend request rejected.');
    }

    // REMOVE FRIEND (either side can remove) -> delete row
    public function remove($friendshipId)
    {
        if ($r = $this->requireLogin()) return $r;

        $uid = (int) session('user_id');
        $friendshipId = (int) $friendshipId;

        $row = DB::table('friendships')->where('id', $friendshipId)->first();
        if (!$row) return redirect('/friends')->with('error', 'Not found.');

        if ((int)$row->requester_id !== $uid && (int)$row->addressee_id !== $uid) {
            return redirect('/friends')->with('error', 'Not allowed.');
        }

        DB::table('friendships')->where('id', $friendshipId)->delete();

        // Activity
        $this->logActivity(
            $uid,
            'friend_removed',
            'Removed a friend',
            '🧹 Connection removed',
            '🧹'
        );

        return redirect('/friends')->with('success', 'Friend removed.');
    }

    // WEEKLY LEADERBOARD (friends + me)
    public function leaderboard()
    {
        if ($r = $this->requireLogin()) return $r;

        $uid = (int) session('user_id');

        $friendRows = DB::table('friendships')
            ->where('status', 'accepted')
            ->where(function ($q) use ($uid) {
                $q->where('requester_id', $uid)->orWhere('addressee_id', $uid);
            })
            ->get();

        $friendIds = $friendRows->map(function ($r) use ($uid) {
            return ((int)$r->requester_id === $uid) ? (int)$r->addressee_id : (int)$r->requester_id;
        })->toArray();

        $allIds = array_values(array_unique(array_merge([$uid], $friendIds)));

        $start = now()->startOfWeek()->toDateString();
        $end   = now()->endOfWeek()->toDateString();

        $weekly = DB::table('habit_logs')
            ->join('habits', 'habits.id', '=', 'habit_logs.habit_id')
            ->whereIn('habits.user_id', $allIds)
            ->where('habit_logs.status', 'done')
            ->whereBetween('habit_logs.log_date', [$start, $end])
            ->select('habits.user_id', DB::raw('COUNT(*) as done_count'))
            ->groupBy('habits.user_id')
            ->get()
            ->keyBy('user_id');

        $rows = DB::table('users')
            ->leftJoin('rewards', 'rewards.user_id', '=', 'users.id')
            ->whereIn('users.id', $allIds)
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COALESCE(rewards.points, 0) as total_points')
            )
            ->get()
            ->map(function ($u) use ($weekly) {
                $done = isset($weekly[$u->id]) ? (int)$weekly[$u->id]->done_count : 0;
                $u->weekly_xp = $done * 10;
                $u->weekly_done = $done;
                return $u;
            })
            ->sortByDesc('weekly_xp')
            ->values();

        return view('friends.leaderboard', [
            'rows' => $rows,
            'weekStart' => $start,
            'weekEnd' => $end
        ]);
    }

    // FRIENDS FEED
    public function feed()
    {
        if ($r = $this->requireLogin()) return $r;

        $uid = (int) session('user_id');

        $friendRows = DB::table('friendships')
            ->where('status', 'accepted')
            ->where(function ($q) use ($uid) {
                $q->where('requester_id', $uid)->orWhere('addressee_id', $uid);
            })
            ->get();

        $friendIds = $friendRows->map(function ($r) use ($uid) {
            return ((int)$r->requester_id === $uid) ? (int)$r->addressee_id : (int)$r->requester_id;
        })->toArray();

        $allIds = array_values(array_unique(array_merge([$uid], $friendIds)));

        $activities = DB::table('activity_logs')
            ->join('users', 'users.id', '=', 'activity_logs.actor_user_id')
            ->whereIn('activity_logs.actor_user_id', $allIds)
            ->orderByDesc('activity_logs.created_at')
            ->select(
                'activity_logs.*',
                'users.name as actor_name',
                'users.email as actor_email'
            )
            ->limit(50)
            ->get();

        return view('friends.feed', compact('activities'));
    }
}
