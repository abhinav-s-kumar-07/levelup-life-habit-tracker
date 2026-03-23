@extends('layouts.landing')

@section('title', 'Create account')

@section('content')
<section class="section">
  <div class="wrap" style="max-width:980px;">
    <div style="display:grid; grid-template-columns: 1.05fr .95fr; gap:14px; align-items:start;">
      <div class="panel" data-reveal>
        <div class="hLabel">Create account</div>
        <div class="hTitle">Start building consistency.</div>
        <p class="hText">
          Create an account to track habits, build streaks, and review weekly progress in a simple dashboard.
        </p>

        <div class="specs" style="margin-top:16px;">
          <div class="spec">
            <div class="k">Fast setup</div>
            <div class="v">Less than a minute</div>
            <div class="d">Add your first habit and begin logging today.</div>
          </div>
          <div class="spec">
            <div class="k">Progress model</div>
            <div class="v">XP and levels</div>
            <div class="d">A measurable growth loop based on completed actions.</div>
          </div>
        </div>

        <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
          <a class="btn" href="{{ url('/') }}">Back to home</a>
          <a class="btn" href="{{ url('/login') }}">I already have an account</a>
        </div>
      </div>

      <div class="panel" data-reveal>
        <h2 class="cardTitle">Create account</h2>
        <div class="muted" style="margin-top:6px;">Enter your details to get started.</div>

        @if ($errors->any())
          <div class="alert" style="margin-top:12px;">
            <div style="font-weight:900;">Validation error</div>
            <ul class="muted" style="margin:8px 0 0 18px;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ url('/register') }}" class="formStack">
          @csrf

          <div>
            <label class="fieldLabel">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required class="textInput">
          </div>

          <div>
            <label class="fieldLabel">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required class="textInput">
          </div>

          <div>
            <label class="fieldLabel">Password</label>
            <input type="password" name="password" placeholder="********" required class="textInput">
          </div>

          <div>
            <label class="fieldLabel">Confirm password</label>
            <input type="password" name="password_confirmation" placeholder="********" required class="textInput">
          </div>

          <button class="btn primary" type="submit" style="width:100%;">Create account</button>

          <div class="muted" style="font-size:13px;">
            Already have an account?
            <a href="{{ url('/login') }}" style="color:var(--text); font-weight:800;">Sign in</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<style>
  @media (max-width: 980px){
    section .wrap > div{ grid-template-columns: 1fr !important; }
  }
</style>
@endsection
