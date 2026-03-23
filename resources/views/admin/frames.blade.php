@extends('layouts.dashboard')

@section('title', 'Frame Catalog')
@section('nav_admin', 'active')
@section('page_title', 'Frame Catalog')
@section('page_subtitle', 'Manage frame style and unlock logic')

@section('top_actions')
  <a class="btn" href="{{ url('/admin') }}">Back to Admin</a>
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

  <style>
    .frame-grid{ display:grid; gap:12px; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); }
    .frame-preview{
      width:90px; height:90px; border-radius:12px; border:1px solid var(--line);
      background:#f7fbff; overflow:hidden; display:grid; place-items:center;
    }
    .frame-preview img{ width:100%; height:100%; object-fit:cover; image-rendering:pixelated; }
    .mini{ font-size:12px; color:var(--muted); }
    .row{ display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
  </style>

  <div class="frame-grid">
    @foreach($frames as $f)
      @php
        $previewSrc = $f->type === 'png'
          ? (file_exists(public_path('frames/' . ($f->asset ?? '')))
            ? asset('frames/' . $f->asset)
            : url('/pixel/frame/' . ($f->asset ?: 'frame_special.png')))
          : url('/pixel/frame/' . (($f->asset === 'flame') ? 'frame_bronze.png' : 'frame_special.png'));
      @endphp
      <div class="card">
        <div class="row" style="justify-content:space-between; align-items:flex-start;">
          <div>
            <div class="cardTitle" style="margin-bottom:4px;">{{ $f->name }}</div>
            <div class="mini">ID {{ $f->id }} | slug: {{ $f->slug }}</div>
            <div class="mini" style="margin-top:4px;">Unlocked by {{ $f->unlocked_count }} users | Equipped by {{ $f->equipped_count }}</div>
          </div>
          <div class="frame-preview">
            <img src="{{ $previewSrc }}" alt="{{ $f->name }}">
          </div>
        </div>

        <form method="POST" action="{{ url('/admin/frames/'.$f->id) }}" style="margin-top:10px; display:grid; gap:8px;">
          @csrf
          <input class="input" name="name" value="{{ $f->name }}" placeholder="Frame name">
          <input class="input" name="slug" value="{{ $f->slug }}" placeholder="slug">
          <div class="row">
            <select class="input" name="type" style="padding:8px;">
              <option value="png" {{ $f->type === 'png' ? 'selected' : '' }}>png</option>
              <option value="css" {{ $f->type === 'css' ? 'selected' : '' }}>css</option>
            </select>
            <input class="input" name="asset" value="{{ $f->asset }}" placeholder="asset (e.g. frame_gold.png)">
          </div>
          <div class="row">
            <select class="input" name="unlock_type" style="padding:8px;">
              <option value="level" {{ $f->unlock_type === 'level' ? 'selected' : '' }}>level</option>
              <option value="xp" {{ $f->unlock_type === 'xp' ? 'selected' : '' }}>xp</option>
              <option value="habit_streak" {{ $f->unlock_type === 'habit_streak' ? 'selected' : '' }}>habit_streak</option>
              <option value="manual" {{ $f->unlock_type === 'manual' ? 'selected' : '' }}>manual</option>
            </select>
            <input class="input" type="number" name="requirement_value" value="{{ $f->requirement_value }}" placeholder="requirement">
          </div>
          <div class="row">
            <button class="btn primary" type="submit">Save Frame</button>
          </div>
        </form>

        <form method="POST" action="{{ url('/admin/frames/'.$f->id.'/toggle-manual') }}" style="margin-top:8px;">
          @csrf
          <button class="btn" type="submit">
            {{ $f->unlock_type === 'manual' ? 'Switch to Auto' : 'Switch to Manual' }}
          </button>
        </form>
      </div>
    @endforeach
  </div>
@endsection
