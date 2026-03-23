@extends('layouts.dashboard')

@section('title', 'Add Habit')
@section('nav_habits', 'active')
@section('page_title', 'Habits')
@section('page_subtitle', 'Create a new habit')

@section('top_actions')
  <a class="btn" href="{{ url('/dashboard') }}">Back to dashboard</a>
@endsection

@section('content')
  <div class="grid">
    <div class="card">
      <div class="cardTitle">New habit</div>
      <div class="muted">Define a habit and its frequency. You can edit it later.</div>

      @if ($errors->any())
        <div class="card" style="margin-top:12px; border-color: rgba(248,113,113,.45); background: rgba(248,113,113,.10);">
          <div style="font-weight:900;">Validation error</div>
          <ul class="muted" style="margin:8px 0 0 18px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ url('/add-habit') }}" style="margin-top:14px; display:grid; gap:12px;">
        @csrf

        <div>
          <div class="muted" style="font-weight:800; font-size:13px; margin-bottom:8px;">Habit name</div>
          <input class="input" name="habit_name" value="{{ old('habit_name') }}" placeholder="e.g. Read 20 minutes">
        </div>

        <div>
          <div class="muted" style="font-weight:800; font-size:13px; margin-bottom:8px;">Frequency</div>
          <select class="input" name="frequency" style="appearance:none;">
            <option value="">Select frequency</option>
            @foreach(($frequencyOptions ?? []) as $opt)
              <option value="{{ $opt }}" {{ old('frequency') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <div class="muted" style="font-weight:800; font-size:13px; margin-bottom:8px;">Difficulty</div>
          <select class="input" name="difficulty" style="appearance:none;">
            <option value="">Select difficulty</option>
            @foreach(($difficultyOptions ?? []) as $opt)
              <option value="{{ $opt }}" {{ old('difficulty', 'Medium') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
          </select>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:6px;">
          <button class="btn primary" type="submit">Create habit</button>
          <a class="btn" href="{{ url('/dashboard') }}">Cancel</a>
        </div>
      </form>
    </div>

    <div class="card">
      <div class="cardTitle">Guidelines</div>
      <div class="muted" style="line-height:1.75;">
        Keep habits specific and measurable. A good habit is something you can complete in a single session.
        Examples: "Walk 30 minutes", "Practice coding 45 minutes", "Meditate 10 minutes".
      </div>

      <div style="margin-top:12px; border:1px solid var(--line); border-radius:18px; padding:12px; background: rgba(255,255,255,.02);">
        <div style="font-weight:900;">Tip</div>
        <div class="muted" style="margin-top:6px;">If you fail often, reduce difficulty, not frequency.</div>
      </div>
    </div>
  </div>
@endsection
