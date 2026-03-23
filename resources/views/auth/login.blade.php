@extends('layouts.landing')

@section('title', 'Login')

@section('content')
<section class="section">
  <div class="wrap" style="max-width:980px;">
    <div style="display:grid; grid-template-columns: 1.05fr .95fr; gap:14px; align-items:start;">
      <div class="panel" data-reveal>
        <div class="hLabel">Welcome back</div>
        <div class="hTitle">Sign in to continue</div>
        <p class="hText">
          Access your dashboard to manage habits, track streaks, and monitor weekly progress.
        </p>

        <div class="specs" style="margin-top:16px;">
          <div class="spec">
            <div class="k">Dashboard</div>
            <div class="v">Analytics overview</div>
            <div class="d">See consistency and streak stability at a glance.</div>
          </div>
          <div class="spec">
            <div class="k">Workflow</div>
            <div class="v">Plan -> Log -> Review</div>
            <div class="d">Simple daily loop designed for long-term use.</div>
          </div>
        </div>

        <div style="margin-top:14px;">
          <a class="btn" href="{{ url('/') }}">Back to home</a>
        </div>
      </div>

      <div class="panel" data-reveal>
        <h2 class="cardTitle">Login</h2>
        <div class="muted" style="margin-top:6px;">Enter your account credentials.</div>

        @if(session('error'))
          <div class="alert" style="margin-top:12px;">
            <div style="font-weight:900;">Login failed</div>
            <div class="muted" style="margin-top:6px;">{{ session('error') }}</div>
          </div>
        @endif

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

        <form method="POST" action="{{ url('/login') }}" class="formStack">
          @csrf

          <div>
            <label class="fieldLabel">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" class="textInput">
          </div>

          <div>
            <label class="fieldLabel">Password</label>
            <input type="password" name="password" placeholder="********" class="textInput">
          </div>

          <button class="btn primary" type="submit" style="width:100%;">Sign in</button>

          <div class="muted" style="font-size:13px;">
            New here?
            <a href="{{ url('/register') }}" style="color:var(--text); font-weight:800;">Create an account</a>
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
