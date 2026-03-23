@extends('layouts.dashboard')

@section('title', 'Profile')
@section('nav_profile', 'active')
@section('page_title', 'Profile')
@section('page_subtitle', 'Account information and security')

@section('top_actions')
  <a class="btn" href="{{ url('/dashboard') }}">Back to dashboard</a>
@endsection

@section('content')
  <div style="margin-bottom:14px;">
    <a class="btn primary" href="{{ url('/profile/customize') }}">Customize Avatar</a>
    <a class="btn" href="{{ url('/u/' . (session('user_id') ?? 0)) }}" style="margin-left:8px;">View Public Profile</a>
  </div>

  <div class="grid">
    <div class="card">
      <div class="cardTitle">Update name</div>
      <div class="muted">Change how your name appears in the app.</div>

      <form method="POST" action="{{ url('/profile/name') }}" style="margin-top:14px; display:grid; gap:12px;">
        @csrf
        <input class="input" name="name" value="{{ session('user_name') ?? '' }}" placeholder="Your name">
        <button class="btn primary" type="submit">Update name</button>
      </form>
    </div>

    <div class="card">
      <div class="cardTitle">Change password</div>
      <div class="muted">Use a strong password that you do not reuse elsewhere.</div>

      <form method="POST" action="{{ url('/profile/password') }}" style="margin-top:14px; display:grid; gap:12px;">
        @csrf
        <input class="input" type="password" name="current_password" placeholder="Current password">
        <input class="input" type="password" name="new_password" placeholder="New password">
        <button class="btn primary" type="submit">Update password</button>
      </form>
    </div>
  </div>
@endsection
