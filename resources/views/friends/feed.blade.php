@extends('layouts.dashboard')

@section('title', 'Friends Feed')
@section('nav_feed', 'active')
@section('page_title', 'Friends')
@section('page_subtitle', 'Social activity and progress')

@section('top_actions')
  <a class="btn" href="{{ url('/friends') }}">Friends</a>
  <a class="btn" href="{{ url('/leaderboard') }}">Leaderboard</a>
@endsection

@section('content')
  <div class="grid" style="grid-template-columns: 1fr;">
    <div class="card">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap;">
        <div>
          <div class="cardTitle" style="margin-bottom:6px;">Friends feed</div>
          <div class="muted">Recent updates from you and your friends.</div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
          <a class="btn" href="{{ url('/dashboard') }}">Dashboard</a>
          <a class="btn primary" href="{{ url('/friends') }}">Manage friends</a>
        </div>
      </div>
    </div>

    <div class="card">
      @if($activities->count() == 0)
        <div class="muted">No activity yet. Complete a habit to start the feed.</div>
      @else
        <div style="display:flex; flex-direction:column; gap:10px;">
          @foreach($activities as $a)
            <div style="padding:12px; border-radius:18px; border:1px solid var(--line); background: rgba(255,255,255,.03); display:flex; justify-content:space-between; gap:12px; align-items:flex-start; flex-wrap:wrap;">
              <div style="display:flex; gap:10px; align-items:flex-start; min-width:260px;">
                <div style="width:40px; height:40px; border-radius:16px; display:grid; place-items:center; border:1px solid var(--line); background: rgba(255,255,255,.04);">
                  <span style="width:10px; height:10px; border-radius:999px; background: rgba(79,124,255,.84); box-shadow: 0 0 0 6px rgba(79,124,255,.14); display:inline-block;"></span>
                </div>
                <div>
                  <div style="font-weight:900; font-size:14px; line-height:1.35;">
                    {{ $a->actor_name ?? 'User' }} | {{ $a->title }}
                  </div>
                  @if(!empty($a->subtitle))
                    <div class="muted" style="font-size:12px; margin-top:6px; line-height:1.6;">{{ $a->subtitle }}</div>
                  @endif
                </div>
              </div>

              <div class="muted" style="font-size:12px; white-space:nowrap;">
                {{ \Carbon\Carbon::parse($a->created_at)->diffForHumans() }}
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
@endsection
