@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('nav_dashboard', 'active')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Your habits for today')

@section('top_actions')
  <a class="btn" href="{{ url('/profile') }}">Profile</a>
  <a class="btn primary" href="{{ url('/add-habit') }}">Add habit</a>
@endsection

@section('content')
  @if(session('success'))
    <div class="card" style="border-color: rgba(44,200,135,.45); background: rgba(44,200,135,.08); margin-bottom:14px;">
      <div style="font-weight:900;">Success</div>
      <div class="muted" style="margin-top:6px;">{{ session('success') }}</div>
    </div>
  @endif

  @if(session('error'))
    <div class="card" style="border-color: rgba(248,113,113,.45); background: rgba(248,113,113,.10); margin-bottom:14px;">
      <div style="font-weight:900;">Notice</div>
      <div class="muted" style="margin-top:6px;">{{ session('error') }}</div>
    </div>
  @endif

  @php
    $avatarSrc = function (string $file): string {
      return file_exists(public_path('avatars/' . $file))
        ? asset('avatars/' . $file)
        : url('/pixel/avatar/' . $file);
    };
    $frameSrc = function (string $file): string {
      return file_exists(public_path('frames/' . $file))
        ? asset('frames/' . $file)
        : url('/pixel/frame/' . $file);
    };
    $totalHabits = count($habits);
    $doneCount = 0;
    foreach ($habits as $h) {
      if (in_array($h->id, $completedToday)) $doneCount++;
    }
    $pendingCount = max(0, $totalHabits - $doneCount);
    $goal = max(1, 7 * $totalHabits);
    $pct = min(100, (int) round(($completedThisWeek / $goal) * 100));
  @endphp

  <style>
    .mini-avatar-wrap{
      width:58px;
      height:58px;
      position:relative;
      border-radius:14px;
      overflow:hidden;
      border:2px solid #d6e2ff;
      background:#f3f8ff;
      flex:0 0 auto;
    }
    .mini-avatar-pic,
    .mini-avatar-frame-png{
      position:absolute;
      image-rendering:pixelated;
    }
    .mini-avatar-pic{
      inset:6px;
      width:calc(100% - 12px);
      height:calc(100% - 12px);
      object-fit:cover;
      border-radius:10px;
      z-index:2;
      border:1px solid #c8d8ff;
      background:#eef5ff;
    }
    .mini-avatar-frame-png{
      inset:0;
      width:100%;
      height:100%;
      object-fit:cover;
      z-index:1;
    }
    .mini-avatar-missing{
      position:absolute;
      inset:0;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:10px;
      font-weight:800;
      color:#5d6a95;
      background:linear-gradient(135deg, #f4f8ff, #ffeef4);
      text-align:center;
      padding:6px;
    }
    .mini-avatar-frame{
      position:absolute;
      inset:0;
      border-radius:14px;
      border:3px solid #8ea8ff;
      pointer-events:none;
    }
    .mini-avatar-frame.bronze{ border-color:#cd7f32; }
    .mini-avatar-frame.silver{ border-color:#b0b7c3; }
    .mini-avatar-frame.gold{ border-color:#d8ad2f; }
    .mini-avatar-frame.flame{ border-color:#ff6b35; }
    .mini-avatar-frame.diamond{ border-color:#57ccff; }
    .mini-avatar-frame.special{ border-style:dashed; border-color:#c77dff; }
  </style>

  <div class="card" style="margin-bottom:14px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
    <div style="display:flex; align-items:center; gap:12px;">
      <a href="{{ url('/profile/customize') }}" class="mini-avatar-wrap">
        <img class="mini-avatar-pic" src="{{ $avatarSrc(($profile->avatar ?? 'avatar1.png')) }}" alt="avatar">
        @if(!empty($profile->frame_type) && $profile->frame_type === 'png' && !empty($profile->frame_asset))
          <img class="mini-avatar-frame-png" src="{{ $frameSrc($profile->frame_asset) }}" alt="frame">
        @elseif(!empty($profile->equipped_frame_id))
          <span class="mini-avatar-frame {{ $profile->frame_asset ?? 'special' }}"></span>
        @endif
      </a>
      <div>
        <div style="font-weight:900;">{{ $profile->name ?? 'Player' }}</div>
        <div class="muted" style="font-size:12px; margin-top:3px;">
          {{ !empty($profile->frame_name) ? $profile->frame_name : 'No frame equipped' }}
        </div>
      </div>
    </div>
    <a class="btn primary" href="{{ url('/profile/customize') }}">Customize Avatar</a>
  </div>

  <div class="grid" style="margin-bottom:14px;">
    <div class="card">
      <div class="muted" style="font-weight:800; font-size:13px;">Today</div>
      <div style="font-size:30px; font-weight:900; margin-top:7px;">{{ $doneCount }} / {{ $totalHabits }}</div>
      <div class="muted" style="margin-top:6px;">{{ $pendingCount }} pending</div>

      <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
        <a class="btn {{ $tab==='all' ? 'primary' : '' }}" href="{{ url('/dashboard?tab=all') }}">All</a>
        <a class="btn {{ $tab==='done' ? 'primary' : '' }}" href="{{ url('/dashboard?tab=done') }}">Done</a>
        <a class="btn {{ $tab==='pending' ? 'primary' : '' }}" href="{{ url('/dashboard?tab=pending') }}">Pending</a>
      </div>
    </div>

    <div class="card">
      <div class="muted" style="font-weight:800; font-size:13px;">XP</div>
      <div style="font-size:30px; font-weight:900; margin-top:7px;">{{ $points }}</div>
      <div class="muted" style="margin-top:6px;">{{ $level }}</div>

      <div class="muted" style="margin-top:12px;">
        Weekly: {{ $xpThisWeek }} XP | {{ $completedThisWeek }} completions | {{ $pct }}%
      </div>

      <div style="margin-top:10px; height:10px; border-radius:999px; border:1px solid var(--line); overflow:hidden;">
        <div style="height:100%; width: {{ $pct }}%; background: rgba(79,124,255,.62);"></div>
      </div>
    </div>
  </div>

  <div style="display:flex; justify-content:space-between; align-items:flex-end; gap:12px; flex-wrap:wrap; margin-bottom:10px;">
    <div>
      <div style="font-weight:900; font-size:16px;">Habits</div>
      <div class="muted" style="margin-top:4px;">Mark items done and keep the chain alive.</div>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
      <a class="btn" href="{{ url('/friends') }}">Friends</a>
      <a class="btn" href="{{ url('/leaderboard') }}">Leaderboard</a>
      <a class="btn" href="{{ url('/feed') }}">Feed</a>
    </div>
  </div>

  <div class="card" style="padding:0; overflow:hidden;">
    @if($totalHabits === 0)
      <div style="padding:16px;" class="muted">No habits yet. Add one to get started.</div>
    @else
      @foreach($habits as $h)
        @php
          $done = in_array($h->id, $completedToday);
          $st = $streaks[$h->id] ?? 0;
        @endphp

        <div class="habit-row" style="
          --d: {{ $loop->index * 70 }}ms;
          padding: 14px 16px;
          border-top: {{ $loop->first ? '0' : '1px solid var(--line)' }};
          display:flex;
          justify-content:space-between;
          gap:14px;
          align-items:center;
          flex-wrap:wrap;">
          <div style="min-width:260px;">
            <div style="font-weight:900;">
              {{ $h->habit_name }}
              <span class="muted" style="font-weight:800; margin-left:8px;">{{ $done ? 'Done' : 'Pending' }}</span>
            </div>
            <div class="muted" style="margin-top:6px; font-size:13px;">
              {{ $h->frequency }} | Streak {{ $st }} day{{ $st == 1 ? '' : 's' }}
            </div>
          </div>

          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            @if(!$done)
              <form method="POST" action="{{ url('/complete/'.$h->id) }}">
                @csrf
                <button class="btn btn-success" data-loading="true" type="submit">Mark done</button>
              </form>
            @else
              <span class="btn btn-success" style="opacity:.62; cursor:default;">Completed</span>
            @endif

            <a class="btn btn-muted" href="{{ url('/habit/edit/'.$h->id) }}">Edit</a>

            <form method="POST" action="{{ url('/habit/delete/'.$h->id) }}"
              onsubmit="return confirm('Delete this habit? This will remove its logs too.');">
              @csrf
              <button class="btn btn-danger" type="submit">Delete</button>
            </form>
          </div>
        </div>
      @endforeach
    @endif
  </div>
@endsection
