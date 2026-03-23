@extends('layouts.dashboard')

@section('title', 'Edit Habit')
@section('nav_habits', 'active')
@section('page_title', 'Habits')
@section('page_subtitle', 'Edit habit details')

@section('top_actions')
  <a class="btn" href="{{ url('/dashboard') }}">Back to dashboard</a>
@endsection

@section('content')
  <div class="grid">
    <div class="card">
      <div class="cardTitle">Edit habit</div>
      <div class="muted">Update the name, frequency, or difficulty. Logs stay unchanged.</div>

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

      <form method="POST" action="{{ url('/habit/update/'.$habit->id) }}" style="margin-top:14px; display:grid; gap:12px;">
        @csrf

        <div>
          <div class="muted" style="font-weight:800; font-size:13px; margin-bottom:8px;">Habit name</div>
          <input class="input" name="habit_name" value="{{ old('habit_name', $habit->habit_name) }}">
        </div>

        <div>
          <div class="muted" style="font-weight:800; font-size:13px; margin-bottom:8px;">Frequency</div>
          <select class="input" name="frequency" style="appearance:none;">
            <option value="">Select frequency</option>
            @foreach(($frequencyOptions ?? []) as $opt)
              <option value="{{ $opt }}" {{ old('frequency', $habit->frequency) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <div class="muted" style="font-weight:800; font-size:13px; margin-bottom:8px;">Difficulty</div>
          <select class="input" name="difficulty" style="appearance:none;">
            <option value="">Select difficulty</option>
            @foreach(($difficultyOptions ?? []) as $opt)
              <option value="{{ $opt }}" {{ old('difficulty', $habit->difficulty ?? 'Medium') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
          </select>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:6px;">
          <button class="btn primary" type="submit">Save changes</button>
          <a class="btn" href="{{ url('/dashboard') }}">Cancel</a>
        </div>
      </form>
    </div>

    <div class="card">
      <div class="cardTitle">Notes</div>
      <div class="muted" style="line-height:1.75;">
        Editing a habit will not delete its completion history. If you want to remove history, delete the habit instead.
      </div>
    </div>
  </div>
@endsection
