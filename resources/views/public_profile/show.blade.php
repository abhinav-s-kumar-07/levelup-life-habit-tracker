@extends('layouts.landing')

@section('title', $user->name . ' | Public Profile')

@section('content')
  <section class="section">
    <div class="wrap" style="max-width:900px;">
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
      @endphp
      <style>
        .public-card{
          border:1px solid var(--line);
          border-radius:20px;
          background:#fff;
          box-shadow:0 14px 32px rgba(89,110,173,.14);
          padding:20px;
          display:flex;
          gap:16px;
          align-items:center;
          flex-wrap:wrap;
        }
        .avatar-shell{
          width:110px;
          height:110px;
          border-radius:16px;
          position:relative;
          overflow:hidden;
          border:2px solid #d6e2ff;
          background:#f3f8ff;
        }
        .avatar-img,
        .avatar-frame{
          position:absolute;
          image-rendering:pixelated;
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
          width:100%;
          height:100%;
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
        .frame-fallback{
          position:absolute;
          inset:0;
          border-radius:16px;
          border:4px solid #8ea8ff;
          z-index:3;
        }
        .frame-fallback.bronze{ border-color:#cd7f32; }
        .frame-fallback.silver{ border-color:#b0b7c3; }
        .frame-fallback.gold{ border-color:#d8ad2f; }
        .frame-fallback.flame{ border-color:#ff6b35; }
        .frame-fallback.diamond{ border-color:#57ccff; }
        .frame-fallback.special{ border-style:dashed; border-color:#c77dff; }
      </style>

      <div class="public-card">
        <div class="avatar-shell">
          <img class="avatar-img" src="{{ $avatarSrc(($user->avatar ?? 'avatar1.png')) }}" alt="avatar">
          @if(!empty($user->frame_type) && $user->frame_type === 'png' && !empty($user->frame_asset))
            <img class="avatar-frame" src="{{ $frameSrc($user->frame_asset) }}" alt="frame">
          @elseif(!empty($user->equipped_frame_id))
            <div class="avatar-frame frame-fallback {{ $user->frame_asset ?? 'special' }}"></div>
          @endif
        </div>

        <div>
          <h2 style="margin:0;">{{ $user->name }}</h2>
          <div class="muted" style="margin-top:6px;">Level {{ $level }} | {{ $xp }} XP</div>
          <div class="muted" style="margin-top:4px;">Best streak: {{ $bestStreak }} day{{ $bestStreak == 1 ? '' : 's' }}</div>
          <div class="muted" style="margin-top:4px;">Frame: {{ $user->frame_name ?? 'None' }}</div>

          <div style="margin-top:12px;">
            @if(session('user_id'))
              <a class="btn primary" href="{{ url('/dashboard') }}">Back to Dashboard</a>
            @else
              <a class="btn primary" href="{{ url('/login') }}">Login</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
