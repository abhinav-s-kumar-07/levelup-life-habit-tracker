@extends('layouts.dashboard')

@section('title', 'Search')
@section('nav_friends', 'active')
@section('page_title', 'Friends')
@section('page_subtitle', 'Search results')

@section('top_actions')
  <a class="btn" href="{{ url('/friends') }}">Back</a>
@endsection

@section('content')
  <div class="grid" style="grid-template-columns: 1fr;">
    <div class="card">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap;">
        <div>
          <div class="cardTitle" style="margin-bottom:6px;">Search results</div>
          <div class="muted">Query: "{{ $q }}"</div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
          <a class="btn" href="{{ url('/friends') }}">Friends</a>
          <a class="btn" href="{{ url('/leaderboard') }}">Leaderboard</a>
        </div>
      </div>
    </div>

    <div class="card">
      @if($results->count() == 0)
        <div class="muted">No users found.</div>
      @else
        <div style="display:flex; flex-direction:column; gap:10px;">
          @foreach($results as $u)
            <div style="padding:12px; border-radius:18px; border:1px solid var(--line); background: rgba(255,255,255,.03); display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center;">
              <div>
                <div style="font-weight:900; font-size:14px;">{{ $u->name ?? 'User' }}</div>
                <div class="muted" style="font-size:12px; margin-top:4px;">{{ $u->email }}</div>
              </div>

              <div>
                @if($u->relation === 'none')
                  <form method="POST" action="{{ url('/friends/request/'.$u->id) }}">
                    @csrf
                    <button class="btn primary" type="submit">Send request</button>
                  </form>
                @elseif($u->relation === 'pending')
                  <span class="btn" style="opacity:.75; cursor:default;">Request pending</span>
                @else
                  <span class="btn" style="opacity:.75; cursor:default;">Already friends</span>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
@endsection
