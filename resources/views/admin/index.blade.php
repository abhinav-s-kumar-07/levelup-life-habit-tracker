@extends('layouts.dashboard')

@section('title', 'Super Admin')
@section('nav_admin', 'active')
@section('page_title', 'Super Admin')
@section('page_subtitle', 'Full platform oversight')

@section('top_actions')
  <a class="btn" href="{{ url('/dashboard') }}">Dashboard</a>
  <a class="btn primary" href="{{ url('/admin/frames') }}">Frame Catalog</a>
@endsection

@section('content')
  @if(session('success'))
    <div class="card" style="margin-bottom:14px; border-color:#bfe8dc; background:#ecfffa;">
      <div style="font-weight:900;">Success</div>
      <div class="muted" style="margin-top:6px;">{{ session('success') }}</div>
    </div>
  @endif

  @if(session('error'))
    <div class="card" style="margin-bottom:14px; border-color:#ffc7d1; background:#fff1f4;">
      <div style="font-weight:900;">Notice</div>
      <div class="muted" style="margin-top:6px;">{{ session('error') }}</div>
    </div>
  @endif

  <div class="grid" style="margin-bottom:14px;">
    <div class="card"><div class="cardTitle">Users</div><div style="font-size:28px; font-weight:900;">{{ $usersCount }}</div></div>
    <div class="card"><div class="cardTitle">Habits</div><div style="font-size:28px; font-weight:900;">{{ $habitsCount }}</div></div>
    <div class="card"><div class="cardTitle">Completions</div><div style="font-size:28px; font-weight:900;">{{ $logsCount }}</div></div>
    <div class="card"><div class="cardTitle">Friendships</div><div style="font-size:28px; font-weight:900;">{{ $friendsCount }}</div></div>
    <div class="card"><div class="cardTitle">Frames</div><div style="font-size:28px; font-weight:900;">{{ $framesCount }}</div></div>
    <div class="card"><div class="cardTitle">Unlocked Frames</div><div style="font-size:28px; font-weight:900;">{{ $unlockedFramesCount }}</div></div>
    <div class="card"><div class="cardTitle">Equipped Frames</div><div style="font-size:28px; font-weight:900;">{{ $equippedFramesCount }}</div></div>
  </div>

  <div class="card" style="margin-bottom:14px;">
    <div class="cardTitle">User Frame Access</div>
    <div class="muted" style="margin-bottom:10px;">Unlock and equip frames for any user.</div>
    <div style="display:grid; gap:8px;">
      @foreach($topUsers as $u)
        <div style="display:flex; justify-content:space-between; border:1px solid var(--line); border-radius:12px; padding:10px;">
          <div>
            <div style="font-weight:800;">{{ $u->name }}</div>
            <div class="muted" style="font-size:12px;">{{ $u->email }}</div>
            <div class="muted" style="font-size:12px; margin-top:3px;">Equipped: {{ $u->equipped_frame_name ?? 'None' }}</div>
          </div>
          <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap; justify-content:flex-end;">
            <div style="font-weight:900;">{{ $u->points }} XP</div>
            <form method="POST" action="{{ url('/admin/frame/unlock') }}" style="display:flex; gap:6px;">
              @csrf
              <input type="hidden" name="user_id" value="{{ $u->id }}">
              <select class="input" name="frame_id" style="width:170px; padding:8px;">
                @foreach($frames as $f)
                  <option value="{{ $f->id }}">{{ $f->name }}</option>
                @endforeach
              </select>
              <button class="btn btn-muted" type="submit">Unlock</button>
            </form>
            <form method="POST" action="{{ url('/admin/frame/equip') }}" style="display:flex; gap:6px;">
              @csrf
              <input type="hidden" name="user_id" value="{{ $u->id }}">
              <select class="input" name="frame_id" style="width:170px; padding:8px;">
                <option value="">None</option>
                @foreach($frames as $f)
                  <option value="{{ $f->id }}">{{ $f->name }}</option>
                @endforeach
              </select>
              <button class="btn primary" type="submit">Equip</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="card">
    <div class="cardTitle">Recent Activity</div>
    <div style="display:grid; gap:8px;">
      @foreach($recentActivities as $a)
        <div style="border:1px solid var(--line); border-radius:12px; padding:10px;">
          <div style="font-weight:800;">{{ $a->actor_name ?? 'Unknown' }} - {{ $a->title }}</div>
          @if(!empty($a->subtitle))
            <div class="muted" style="font-size:12px; margin-top:4px;">{{ $a->subtitle }}</div>
          @endif
          <div class="muted" style="font-size:11px; margin-top:4px;">{{ $a->created_at }}</div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
