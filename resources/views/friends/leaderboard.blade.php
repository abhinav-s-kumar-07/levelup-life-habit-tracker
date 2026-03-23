@extends('layouts.dashboard')

@section('title', 'Leaderboard')
@section('nav_leaderboard', 'active')
@section('page_title', 'Leaderboard')
@section('page_subtitle', $weekStart.' to '.$weekEnd.' | ranked by weekly XP')

@section('top_actions')
  <a class="btn" href="{{ url('/friends') }}">Friends</a>
  <a class="btn" href="{{ url('/feed') }}">Feed</a>
@endsection

@section('content')
  <div class="grid" style="grid-template-columns: 1fr;">
    <div class="card">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap;">
        <div>
          <div class="cardTitle" style="margin-bottom:6px;">Weekly leaderboard</div>
          <div class="muted">{{ $weekStart }} to {{ $weekEnd }} | Ranked by weekly XP</div>
        </div>
        <a class="btn" href="{{ url('/dashboard') }}">Dashboard</a>
      </div>
    </div>

    <div class="card">
      @if($rows->count() == 0)
        <div class="muted">No data yet.</div>
      @else
        <div style="overflow-x:auto;">
          <table style="width:100%; border-collapse:separate; border-spacing:0; border:1px solid var(--line); border-radius:16px; overflow:hidden;">
            <thead>
              <tr style="background: rgba(255,255,255,.03);">
                <th style="padding:12px; text-align:left; font-size:12px; color:var(--muted);">Rank</th>
                <th style="padding:12px; text-align:left; font-size:12px; color:var(--muted);">User</th>
                <th style="padding:12px; text-align:right; font-size:12px; color:var(--muted);">Weekly XP</th>
                <th style="padding:12px; text-align:right; font-size:12px; color:var(--muted);">Done</th>
                <th style="padding:12px; text-align:right; font-size:12px; color:var(--muted);">Total XP</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rows as $i => $r)
                <tr>
                  <td style="padding:12px; border-top:1px solid var(--line); font-weight:900;">{{ $i + 1 }}</td>
                  <td style="padding:12px; border-top:1px solid var(--line);">
                    <div style="font-weight:900; font-size:14px;">{{ $r->name ?? 'User' }}</div>
                    <div class="muted" style="font-size:12px; margin-top:4px;">{{ $r->email }}</div>
                  </td>
                  <td style="padding:12px; border-top:1px solid var(--line); text-align:right; font-weight:900;">{{ $r->weekly_xp }}</td>
                  <td style="padding:12px; border-top:1px solid var(--line); text-align:right; font-weight:900;">{{ $r->weekly_done }}</td>
                  <td style="padding:12px; border-top:1px solid var(--line); text-align:right; font-weight:900;">{{ $r->total_points }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
@endsection
