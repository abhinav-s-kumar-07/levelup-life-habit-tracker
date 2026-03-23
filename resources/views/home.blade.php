@extends('layouts.app')

@section('title', 'Home')

@push('styles')
<style>
  .homeGrid{
    display:grid;
    grid-template-columns:1.06fr .94fr;
    gap:14px;
    align-items:stretch;
  }

  .panel{
    border:1px solid var(--line);
    border-radius:22px;
    background: linear-gradient(180deg, #ffffff, #fff8ff);
    box-shadow: 0 14px 35px rgba(89,110,173,.16);
    padding:22px;
  }

  .kicker{
    display:inline-flex;
    align-items:center;
    gap:8px;
    border:1px solid var(--line);
    background:#f6f9ff;
    border-radius:999px;
    padding:7px 10px;
    font-size:12px;
    color:var(--muted);
    font-weight:800;
  }
  .kickerDot{
    width:8px;
    height:8px;
    border-radius:999px;
    background:var(--accent-2);
    box-shadow:0 0 0 5px rgba(44,200,135,.14);
  }

  .heroTitle{
    margin:12px 0 10px;
    font-family:"Sora", system-ui, sans-serif;
    font-size:40px;
    line-height:1.05;
    letter-spacing:-.5px;
  }
  .heroSub{
    margin:0;
    color:var(--muted);
    font-size:15px;
    line-height:1.7;
    max-width:58ch;
  }

  .spriteRow{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-top:16px;
  }
  .spriteCard{
    border:1px solid var(--line);
    border-radius:16px;
    background:#f8fbff;
    padding:8px;
    width:84px;
    height:84px;
    display:flex;
    align-items:center;
    justify-content:center;
  }

  .cta{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-top:16px;
  }

  .steps{
    margin-top:12px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
  }
  .step{
    border:1px solid var(--line);
    border-radius:14px;
    background:#f8fbff;
    padding:12px;
  }
  .stepNo{
    font-size:12px;
    color:var(--muted);
    font-weight:800;
  }
  .stepTitle{
    margin-top:6px;
    font-weight:900;
    font-size:14px;
  }
  .stepText{
    margin-top:4px;
    color:var(--muted);
    font-size:12px;
    line-height:1.6;
  }

  @media (max-width: 980px){
    .homeGrid{ grid-template-columns:1fr; }
    .heroTitle{ font-size:32px; }
  }
</style>
@endpush

@section('content')
  <div class="homeGrid">
    <section class="panel">
      <span class="kicker"><span class="kickerDot"></span>New quest starts today</span>
      <h1 class="heroTitle">Build habits like a game.</h1>
      <p class="heroSub">
        Track your habits, protect streaks, earn points, and level up with a clean pixel-inspired flow.
      </p>

      <div class="spriteRow">
        <div class="spriteCard">@include('partials.pixel.knight')</div>
        <div class="spriteCard">@include('partials.pixel.mage')</div>
        <div class="spriteCard">@include('partials.pixel.pet')</div>
      </div>

      <div class="cta">
        <a href="{{ url('/register') }}" class="btn primary">Sign up</a>
        <a href="{{ url('/login') }}" class="btn">Log in</a>
      </div>
    </section>

    <section class="panel">
      <h2 class="pageTitle" style="font-size:24px; margin:0;">How it works</h2>
      <p class="pageSub" style="margin-top:8px;">A four-step loop that keeps execution simple every day.</p>

      <div class="steps">
        <article class="step">
          <div class="stepNo">Step 1</div>
          <div class="stepTitle">Add habits</div>
          <div class="stepText">Create small, clear goals that you can complete in one session.</div>
        </article>
        <article class="step">
          <div class="stepNo">Step 2</div>
          <div class="stepTitle">Complete</div>
          <div class="stepText">Mark each habit done to lock in daily progress and consistency.</div>
        </article>
        <article class="step">
          <div class="stepNo">Step 3</div>
          <div class="stepTitle">Earn points</div>
          <div class="stepText">Completions convert to XP so growth is visible week by week.</div>
        </article>
        <article class="step">
          <div class="stepNo">Step 4</div>
          <div class="stepTitle">Keep streaks</div>
          <div class="stepText">Maintain your chain and build momentum over time.</div>
        </article>
      </div>
    </section>
  </div>
@endsection
