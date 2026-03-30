@extends('layouts.landing')

@section('title', 'Register')

@section('content')
<section class="section">
  <div class="wrap" style="max-width:500px; margin:auto;">
    <div class="panel" data-reveal>
      <h2 class="cardTitle">Create Account</h2>
      <div class="muted" style="margin-top:6px;">Fill in your details to register.</div>

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
          <input type="text" name="name" value="{{ old('name') }}" placeholder="Your Name" class="textInput" required>
        </div>

        <div>
          <label class="fieldLabel">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" class="textInput" required>
        </div>

        <div>
          <label class="fieldLabel">Password</label>
          <input type="password" name="password" placeholder="********" class="textInput" required>
        </div>

        <div>
          <label class="fieldLabel">Confirm Password</label>
          <input type="password" name="password_confirmation" placeholder="********" class="textInput" required>
        </div>

        <button class="btn primary" type="submit" style="width:100%; margin-top:12px;">Register</button>

        <div class="muted" style="font-size:13px; margin-top:6px;">
          Already have an account? 
          <a href="{{ url('/login') }}" style="color:var(--text); font-weight:800;">Sign in</a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection