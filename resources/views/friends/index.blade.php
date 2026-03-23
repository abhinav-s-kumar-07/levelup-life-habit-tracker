@extends('layouts.dashboard')

@section('title', 'Friends')
@section('nav_friends', 'active')
@section('page_title', 'Friends')
@section('page_subtitle', 'Add friends and compare progress')

@section('top_actions')
  <a class="btn" href="{{ url('/feed') }}">Feed</a>
  <a class="btn" href="{{ url('/leaderboard') }}">Leaderboard</a>
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

  <div class="card">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap;">
      <div>
        <div class="cardTitle" style="margin-bottom:6px;">Find people</div>
        <div class="muted">Search by name or email, then send a request.</div>
      </div>
      <a class="btn" href="{{ url('/dashboard') }}">Dashboard</a>
    </div>

    <form method="GET" action="{{ url('/friends/search') }}" style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
      <input class="input" name="q" placeholder="Search by name or email" style="flex:1; min-width:240px;">
      <button class="btn primary" type="submit">Search</button>
    </form>
  </div>

  <div class="grid" style="grid-template-columns: 1fr; margin-top:14px;">
    <div class="card">
      <div class="cardTitle">Friend requests</div>
      <div class="muted">Incoming requests waiting for you.</div>

      @if($incoming->count() == 0)
        <div class="muted" style="margin-top:12px;">No new requests.</div>
      @else
        <div style="margin-top:12px; display:flex; flex-direction:column; gap:10px;">
          @foreach($incoming as $req)
            <div style="padding:12px; border-radius:18px; border:1px solid var(--line); background: rgba(255,255,255,.03); display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center;">
              <div>
                <div style="font-weight:900; font-size:14px;">{{ $req->name ?? 'User' }}</div>
                <div class="muted" style="font-size:12px; margin-top:4px;">{{ $req->email }}</div>
              </div>

              <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <form method="POST" action="{{ url('/friends/accept/'.$req->friendship_id) }}">
                  @csrf
                  <button class="btn primary" type="submit">Accept</button>
                </form>
                <form method="POST" action="{{ url('/friends/reject/'.$req->friendship_id) }}">
                  @csrf
                  <button class="btn btn-danger" type="submit">Reject</button>
                </form>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <div class="card">
      <div class="cardTitle">Sent requests</div>
      <div class="muted">Requests you have sent (pending).</div>

      @if($outgoing->count() == 0)
        <div class="muted" style="margin-top:12px;">No pending sent requests.</div>
      @else
        <div style="margin-top:12px; display:flex; flex-direction:column; gap:10px;">
          @foreach($outgoing as $req)
            <div style="padding:12px; border-radius:18px; border:1px solid var(--line); background: rgba(255,255,255,.03); display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center;">
              <div>
                <div style="font-weight:900; font-size:14px;">{{ $req->name ?? 'User' }}</div>
                <div class="muted" style="font-size:12px; margin-top:4px;">{{ $req->email }}</div>
              </div>
              <div class="muted" style="font-weight:800;">Pending</div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <div class="card">
      <div class="cardTitle">Your friends</div>
      <div class="muted">People you are connected with.</div>

      @if($friends->count() == 0)
        <div class="muted" style="margin-top:12px;">No friends yet. Search and add someone.</div>
      @else
        <div style="margin-top:12px; display:flex; flex-direction:column; gap:10px;">
          @foreach($friends as $f)
            <div style="padding:12px; border-radius:18px; border:1px solid var(--line); background: rgba(255,255,255,.03); display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center;">
              <div>
                <div style="font-weight:900; font-size:14px;">{{ $f->name ?? 'User' }}</div>
                <div class="muted" style="font-size:12px; margin-top:4px;">{{ $f->email }}</div>
              </div>

              <form method="POST" action="{{ url('/friends/remove/'.$f->friendship_id) }}" onsubmit="return confirm('Remove this friend?');">
                @csrf
                <button class="btn btn-danger" type="submit">Remove</button>
              </form>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
@endsection
