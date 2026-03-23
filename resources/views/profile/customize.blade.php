@extends('layouts.dashboard')

@section('title', 'Customize Avatar')
@section('nav_profile', 'active')
@section('page_title', 'Avatar Studio')
@section('page_subtitle', 'Customize your pixel identity')

@section('top_actions')
  <a class="btn" href="{{ url('/profile') }}">Back to profile</a>
  <a class="btn" href="{{ url('/dashboard') }}">Dashboard</a>
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

  @php
    $equipped = collect($frames)->firstWhere('is_equipped', true);
    $selectedAvatar = $user->avatar ?? 'avatar1.png';
    $selectedAvatarName = $avatarNames[$selectedAvatar] ?? pathinfo($selectedAvatar, PATHINFO_FILENAME);
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
    $requirementText = function ($frame) {
      if ($frame->unlock_type === 'xp') return 'Unlock at '.$frame->requirement_value.' XP';
      if ($frame->unlock_type === 'level') return 'Unlock at Level '.$frame->requirement_value;
      if ($frame->unlock_type === 'habit_streak') return 'Unlock at '.$frame->requirement_value.'-day streak';
      return 'Special unlock';
    };
  @endphp

  <style>
    .studio-grid{ display:grid; gap:14px; grid-template-columns:1fr; }
    .player-card{
      display:flex;
      align-items:center;
      gap:16px;
      flex-wrap:wrap;
    }
    .avatar-shell{
      width:110px;
      height:110px;
      position:relative;
      border-radius:16px;
      overflow:hidden;
      border:2px solid #d6e2ff;
      background:#f3f8ff;
      box-shadow:0 10px 24px rgba(88,110,172,.18);
    }
    .avatar-img,
    .avatar-frame{
      position:absolute;
      width:100%;
      height:100%;
      image-rendering: pixelated;
    }
    .avatar-img{
      inset:10px;
      width:calc(100% - 20px);
      height:calc(100% - 20px);
      object-fit:cover;
      border-radius:12px;
      z-index:2;
      border:1px solid #c8d8ff;
      background:#eef5ff;
    }
    .avatar-frame{
      inset:0;
      object-fit:cover;
      z-index:1;
    }
    .avatar-missing{
      position:absolute;
      inset:0;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:11px;
      font-weight:800;
      color:#5d6a95;
      background:linear-gradient(135deg, #f4f8ff, #ffeef4);
      text-align:center;
      padding:6px;
    }
    .avatar-fallback{
      position:absolute;
      inset:0;
      border:4px solid #8ea8ff;
      border-radius:16px;
      pointer-events:none;
      z-index:3;
    }
    .frame-fallback.bronze{ border:4px solid #cd7f32; border-radius:16px; }
    .frame-fallback.silver{ border:4px solid #b0b7c3; border-radius:16px; }
    .frame-fallback.gold{ border:4px solid #d8ad2f; border-radius:16px; }
    .frame-fallback.flame{ border:4px solid #ff6b35; border-radius:16px; box-shadow:0 0 0 2px #ffd166 inset; }
    .frame-fallback.diamond{ border:4px solid #57ccff; border-radius:16px; }
    .frame-fallback.special{ border:4px dashed #c77dff; border-radius:16px; }

    .avatar-grid,
    .frame-grid{
      display:grid;
      grid-template-columns:repeat(auto-fill, minmax(140px, 1fr));
      gap:10px;
    }
    .avatar-item,
    .frame-item{
      border:1px solid var(--line);
      border-radius:14px;
      background:#fff;
      padding:10px;
      transition:.14s ease;
    }
    .avatar-item:hover,
    .frame-item:hover{
      transform:translateY(-2px);
      box-shadow:0 10px 20px rgba(106,128,195,.16);
    }
    .thumb{
      width:74px;
      height:74px;
      margin:0 auto 8px;
      border-radius:12px;
      border:1px solid #d6e2ff;
      overflow:hidden;
      background:#f5f9ff;
      position:relative;
    }
    .thumb img{
      width:100%;
      height:100%;
      object-fit:cover;
      image-rendering: pixelated;
    }
    .locked{
      opacity:.35;
      filter:grayscale(1);
    }
    .small{ font-size:12px; color:var(--muted); }
    .frame-chip{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:4px 8px;
      border-radius:999px;
      font-size:11px;
      font-weight:800;
      margin:6px auto 8px;
      border:1px solid #d6e2ff;
      background:#f4f8ff;
      color:#51608e;
    }
    .frame-chip.bronze{ background:#fff1e6; border-color:#f5c79e; color:#9b5f25; }
    .frame-chip.silver{ background:#f5f7fb; border-color:#ccd3df; color:#667386; }
    .frame-chip.gold{ background:#fff6da; border-color:#f2dc8d; color:#8a6a10; }
    .frame-chip.flame{ background:#fff0ea; border-color:#ffb79a; color:#b8522b; }
    .frame-chip.diamond{ background:#ecfbff; border-color:#9ee6ff; color:#2f7f9e; }
    .frame-chip.special{ background:#f9f0ff; border-color:#d8b2ff; color:#7b40a3; }
  </style>

  <div class="studio-grid">
    <div class="card player-card">
      <div class="avatar-shell">
        <img class="avatar-img" src="{{ $avatarSrc(($user->avatar ?? 'avatar1.png')) }}" alt="avatar">

        @if($equipped && $equipped->type === 'png' && !empty($equipped->asset))
          <img class="avatar-frame" src="{{ $frameSrc($equipped->asset) }}" alt="frame">
        @elseif($equipped)
          <div class="avatar-frame frame-fallback {{ $equipped->asset ?? 'special' }}"></div>
        @else
          <div class="avatar-fallback"></div>
        @endif
      </div>

      <div>
        <div style="font-weight:900; font-size:18px;">{{ $user->name }}</div>
        <div class="muted" style="margin-top:4px;">Level {{ $level }} | {{ $xp }} XP</div>
        <div class="muted" style="margin-top:4px;">Best streak: {{ $bestStreak }} day{{ $bestStreak == 1 ? '' : 's' }}</div>
        <div class="muted" style="margin-top:4px;">Avatar: {{ $selectedAvatarName }}</div>
        <div class="muted" style="margin-top:4px;">
          Equipped frame: {{ $equipped->name ?? 'None' }}
        </div>
      </div>
    </div>

    <div class="card">
      <div class="cardTitle">Choose Avatar</div>
      <div class="avatar-grid" style="margin-top:10px;">
        @foreach($avatars as $avatar)
          <div class="avatar-item">
            <div class="thumb">
              <img src="{{ $avatarSrc($avatar) }}" alt="{{ $avatar }}">
            </div>
            <div style="text-align:center; font-weight:800; font-size:12px; margin-bottom:3px;">
              {{ $avatarNames[$avatar] ?? pathinfo($avatar, PATHINFO_FILENAME) }}
            </div>
            <div class="small" style="text-align:center; margin-bottom:8px;">{{ $avatar }}</div>
            @if(($user->avatar ?? 'avatar1.png') === $avatar)
              <button class="btn btn-muted" style="width:100%;" disabled>Selected</button>
            @else
              <form method="POST" action="{{ url('/profile/avatar') }}">
                @csrf
                <input type="hidden" name="avatar" value="{{ $avatar }}">
                <button class="btn primary" type="submit" style="width:100%;">Select</button>
              </form>
            @endif
          </div>
        @endforeach
      </div>
    </div>

    <div class="card">
      <div class="cardTitle">Frames</div>
      <div class="frame-grid" style="margin-top:10px;">
        @foreach($frames as $frame)
          <div class="frame-item {{ !$frame->is_unlocked ? 'locked' : '' }}">
            <div class="thumb">
              @if($frame->type === 'png' && !empty($frame->asset))
                <img src="{{ $frameSrc($frame->asset) }}" alt="{{ $frame->name }}">
              @else
                <div class="avatar-frame frame-fallback {{ $frame->asset ?? 'special' }}"></div>
              @endif
            </div>
            <div style="font-weight:800; font-size:13px; text-align:center;">{{ $frame->name }}</div>
            <div class="frame-chip {{ $frame->asset ?? 'special' }}">{{ strtoupper($frame->asset ?? 'special') }}</div>
            <div class="small" style="text-align:center; margin:6px 0 8px;">{{ $requirementText($frame) }}</div>

            @if($frame->is_unlocked)
              @if($frame->is_equipped)
                <button class="btn btn-muted" style="width:100%;" disabled>Equipped</button>
              @else
                <form method="POST" action="{{ url('/profile/frame') }}">
                  @csrf
                  <input type="hidden" name="frame_id" value="{{ $frame->id }}">
                  <button class="btn primary" type="submit" style="width:100%;">Equip</button>
                </form>
              @endif
            @else
              <button class="btn btn-muted" style="width:100%;" disabled>Locked</button>
            @endif
          </div>
        @endforeach
      </div>

      <form method="POST" action="{{ url('/profile/frame') }}" style="margin-top:12px;">
        @csrf
        <input type="hidden" name="frame_id" value="">
        <button class="btn" type="submit">Remove equipped frame</button>
      </form>
    </div>
  </div>
@endsection
