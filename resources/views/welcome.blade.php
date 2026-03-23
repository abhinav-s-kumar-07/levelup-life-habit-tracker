@extends('layouts.landing')

@section('title', 'Home')

@section('content')
  <section class="section home-hero" data-reveal>
    <div class="wrap">
      <div class="home-hero__stage" id="homeHeroStage">
        <span class="home-layer glow-a" data-home-depth="0.14"></span>
        <span class="home-layer glow-b" data-home-depth="0.24"></span>
        <span class="home-layer ring-a" data-home-depth="0.32"></span>
        <span class="home-layer ring-b" data-home-depth="0.42"></span>
        <span class="home-layer streak" data-home-depth="0.2"></span>

        <div style="display:grid; grid-template-columns: 1.1fr .9fr; gap:14px; align-items:stretch; position:relative; z-index:2;">
          <div class="panel home-panel" style="padding:26px;">
            <div class="hLabel">Consistency first</div>
            <h1 class="hTitle" style="margin-top:10px;">Track habits. Build momentum.</h1>
            <p class="hText" style="max-width:58ch;">
              A clean daily system for logging habits, protecting streaks, and leveling up with visible weekly progress.
            </p>

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
              <a class="btn primary" href="{{ url('/register') }}">Get started</a>
              <a class="btn" href="{{ url('/login') }}">Login</a>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px;">
              <span class="btn" style="cursor:default;">Daily logging</span>
              <span class="btn" style="cursor:default;">Streak tracking</span>
              <span class="btn" style="cursor:default;">XP progression</span>
            </div>
          </div>

          <div class="panel home-panel" style="padding:20px;">
            <div class="cardTitle">Weekly snapshot</div>
            <p class="muted" style="margin-top:6px;">Preview of how your dashboard stays focused on action.</p>

            <div style="display:grid; gap:10px; margin-top:12px;">
              <div style="border:1px solid var(--line); border-radius:16px; padding:12px; background:rgba(255,255,255,.03);">
                <div style="display:flex; justify-content:space-between; gap:10px;">
                  <span class="muted">Consistency</span>
                  <strong>72%</strong>
                </div>
                <div style="margin-top:9px; border:1px solid var(--line); border-radius:999px; height:10px; overflow:hidden;">
                  <div style="height:100%; width:72%; background:rgba(79,124,255,.62);"></div>
                </div>
              </div>

              <div style="border:1px solid var(--line); border-radius:16px; padding:12px; background:rgba(255,255,255,.03);">
                <div style="display:flex; justify-content:space-between; gap:10px;">
                  <span class="muted">Habits done</span>
                  <strong>18 / 24</strong>
                </div>
                <div style="margin-top:9px; border:1px solid var(--line); border-radius:999px; height:10px; overflow:hidden;">
                  <div style="height:100%; width:75%; background:rgba(49,196,141,.62);"></div>
                </div>
              </div>

              <div style="border:1px solid var(--line); border-radius:16px; padding:12px; background:rgba(255,255,255,.03);">
                <div style="display:flex; justify-content:space-between; gap:10px;">
                  <span class="muted">Current XP</span>
                  <strong>640</strong>
                </div>
                <div style="margin-top:9px; border:1px solid var(--line); border-radius:999px; height:10px; overflow:hidden;">
                  <div style="height:100%; width:84%; background:rgba(79,124,255,.62);"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top:0;" data-reveal>
    <div class="wrap">
      <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:12px;">
        <div class="spec">
          <div class="k">Daily Focus</div>
          <div class="v">Single screen workflow</div>
          <div class="d">Open dashboard, complete habits, see immediate progress.</div>
        </div>
        <div class="spec">
          <div class="k">Momentum</div>
          <div class="v">Streak visibility</div>
          <div class="d">Each habit keeps a clear streak so consistency is obvious.</div>
        </div>
        <div class="spec">
          <div class="k">Growth Loop</div>
          <div class="v">XP and levels</div>
          <div class="d">Completions convert into XP for weekly and long-term feedback.</div>
        </div>
      </div>
    </div>
  </section>

  <section class="section why-works" data-reveal>
    <div class="wrap">
      <div class="why-works__head">
        <div class="hLabel">Why this works</div>
        <h2 class="hTitle">Small actions become visible progress</h2>
        <p class="hText">
          This system rewards consistency, protects your streaks, and gives immediate feedback so habits feel energizing, not boring.
        </p>
      </div>

      <div class="why-works__stage" id="whyWorksStage">
        <div class="parallax-layer layer-glow" data-depth="0.1"></div>
        <div class="parallax-layer layer-orbit layer-one" data-depth="0.25"></div>
        <div class="parallax-layer layer-orbit layer-two" data-depth="0.4"></div>
        <div class="parallax-layer layer-orbit layer-three" data-depth="0.55"></div>

        <div class="why-works__timeline panel">
          <div class="timeline-item" data-depth="0.15">
            <div class="timeline-day">Day 1</div>
            <div class="timeline-copy">
              <h3>Start tiny</h3>
              <p>Choose 2 habits you can complete in under 5 minutes.</p>
            </div>
            <span class="timeline-chip">+20 XP</span>
          </div>
          <div class="timeline-item" data-depth="0.25">
            <div class="timeline-day">Day 7</div>
            <div class="timeline-copy">
              <h3>Protect streaks</h3>
              <p>Visible chains keep momentum and reduce skipped days.</p>
            </div>
            <span class="timeline-chip">Streak x7</span>
          </div>
          <div class="timeline-item" data-depth="0.35">
            <div class="timeline-day">Day 30</div>
            <div class="timeline-copy">
              <h3>Feel identity shift</h3>
              <p>XP, levels, and progress views make consistency rewarding.</p>
            </div>
            <span class="timeline-chip">Level up</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  @push('styles')
    <style>
      .home-hero{
        padding-top:34px;
      }
      .home-hero__stage{
        position:relative;
        overflow:hidden;
        border:1px solid #cfdcff;
        border-radius:30px;
        background:
          radial-gradient(circle at 9% 12%, rgba(255,102,145,.24), transparent 44%),
          radial-gradient(circle at 82% 10%, rgba(71,148,255,.22), transparent 44%),
          linear-gradient(180deg, #fffdfd 0%, #f6f9ff 100%);
        padding:18px;
        box-shadow:0 16px 40px rgba(93,117,191,.15);
      }
      .home-panel{
        background:linear-gradient(180deg, rgba(255,255,255,.95), rgba(255,247,255,.92));
        backdrop-filter: blur(4px);
      }
      .home-layer{
        position:absolute;
        z-index:1;
        pointer-events:none;
        will-change:transform;
      }
      .glow-a{
        width:290px;
        height:290px;
        top:-90px;
        left:-70px;
        border-radius:999px;
        background:radial-gradient(circle, rgba(255,107,138,.3), transparent 70%);
      }
      .glow-b{
        width:360px;
        height:360px;
        right:-120px;
        top:-120px;
        border-radius:999px;
        background:radial-gradient(circle, rgba(79,124,255,.3), transparent 68%);
      }
      .ring-a,
      .ring-b{
        border-radius:999px;
        border:1px solid rgba(109,136,220,.25);
        box-shadow:inset 0 0 0 14px rgba(255,255,255,.18);
      }
      .ring-a{
        width:148px;
        height:148px;
        left:33%;
        top:18px;
      }
      .ring-b{
        width:104px;
        height:104px;
        right:28%;
        bottom:20px;
        border-color:rgba(255,122,163,.26);
      }
      .streak{
        left:40%;
        top:0;
        width:340px;
        height:100%;
        background:linear-gradient(120deg, transparent, rgba(255,255,255,.55), transparent);
        transform:skewX(-20deg);
        opacity:.56;
      }

      .why-works{
        position:relative;
        overflow:hidden;
      }
      .why-works__head{
        text-align:center;
        max-width:740px;
        margin:0 auto 20px;
      }
      .why-works__head .hText{
        margin-top:10px;
      }
      .why-works__stage{
        position:relative;
        min-height:460px;
        border:1px solid var(--line);
        border-radius:28px;
        background:
          radial-gradient(circle at 12% 15%, rgba(255, 107, 138, 0.22), transparent 45%),
          radial-gradient(circle at 85% 22%, rgba(79, 124, 255, 0.22), transparent 40%),
          linear-gradient(180deg, #ffffff 0%, #f5f9ff 100%);
        box-shadow:var(--shadow);
        padding:26px;
        isolation:isolate;
      }

      .parallax-layer{
        position:absolute;
        pointer-events:none;
        z-index:0;
        transition: transform .2s ease-out;
      }
      .layer-glow{
        inset:8% 12%;
        border-radius:32px;
        background:linear-gradient(135deg, rgba(255,107,138,.09), rgba(79,124,255,.1));
        border:1px solid rgba(255,255,255,.5);
      }
      .layer-orbit{
        width:130px;
        height:130px;
        border-radius:999px;
        border:1px solid rgba(79,124,255,.2);
        background:rgba(255,255,255,.45);
        box-shadow:inset 0 0 0 10px rgba(79,124,255,.06);
      }
      .layer-one{
        top:18px;
        right:38px;
      }
      .layer-two{
        bottom:26px;
        left:48px;
        width:96px;
        height:96px;
      }
      .layer-three{
        top:50%;
        right:24%;
        width:76px;
        height:76px;
        border-color:rgba(20,200,168,.24);
        box-shadow:inset 0 0 0 8px rgba(20,200,168,.08);
      }

      .why-works__timeline{
        position:relative;
        z-index:2;
        max-width:840px;
        margin:0 auto;
        background:rgba(255,255,255,.8);
        backdrop-filter: blur(3px);
      }
      .timeline-item{
        display:grid;
        grid-template-columns: 94px 1fr auto;
        align-items:center;
        gap:14px;
        border:1px solid var(--line);
        border-radius:16px;
        background:#ffffff;
        padding:14px;
      }
      .timeline-item + .timeline-item{
        margin-top:10px;
      }
      .timeline-day{
        border:1px solid var(--line);
        border-radius:999px;
        text-align:center;
        padding:8px 10px;
        font-size:12px;
        font-weight:800;
        color:#5f6f9b;
        background:#f8fbff;
      }
      .timeline-copy h3{
        margin:0;
        font-family:"Sora", system-ui, sans-serif;
        font-size:16px;
      }
      .timeline-copy p{
        margin:4px 0 0;
        font-size:13px;
        color:var(--muted);
      }
      .timeline-chip{
        border-radius:999px;
        border:1px solid #d0ddff;
        padding:7px 10px;
        background:linear-gradient(135deg, #ffe9ef, #ecf2ff);
        font-size:12px;
        font-weight:800;
        color:#52629a;
      }

      @media (max-width: 980px){
        section .wrap > div[style*="grid-template-columns"]{
          grid-template-columns: 1fr !important;
        }
        .home-hero__stage{
          padding:12px;
        }
        .streak{
          left:8%;
          width:180px;
          opacity:.34;
        }
        .why-works__stage{
          min-height:0;
          padding:18px;
        }
        .layer-one{
          top:8px;
          right:14px;
        }
        .layer-two{
          left:10px;
          bottom:14px;
        }
        .layer-three{
          right:8%;
        }
      }
      @media (max-width: 680px){
        .timeline-item{
          grid-template-columns: 1fr;
          align-items:start;
        }
      }
    </style>
  @endpush

  @push('scripts')
    <script>
      (function(){
        const heroStage = document.getElementById('homeHeroStage');
        if (heroStage && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
          const layers = heroStage.querySelectorAll('[data-home-depth]');
          const clamp = (value, min, max) => Math.min(max, Math.max(min, value));
          const moveHero = (x, y) => {
            layers.forEach((layer) => {
              const depth = Number(layer.getAttribute('data-home-depth')) || 0;
              layer.style.transform = `translate3d(${clamp(x * depth, -38, 38)}px, ${clamp(y * depth, -30, 30)}px, 0)`;
            });
          };

          const onHeroScroll = () => {
            const rect = heroStage.getBoundingClientRect();
            const progress = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
            const amount = clamp((progress - 0.5) * 44, -22, 22);
            moveHero(amount, amount * -0.72);
          };
          onHeroScroll();
          window.addEventListener('scroll', onHeroScroll, { passive: true });

          heroStage.addEventListener('mousemove', (event) => {
            const rect = heroStage.getBoundingClientRect();
            const px = (event.clientX - rect.left) / rect.width - 0.5;
            const py = (event.clientY - rect.top) / rect.height - 0.5;
            moveHero(px * 34, py * 30);
          });
        }

        const stage = document.getElementById('whyWorksStage');
        if (!stage || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        const items = stage.querySelectorAll('[data-depth]');
        const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

        const moveLayers = (px, py) => {
          items.forEach((el) => {
            const depth = Number(el.getAttribute('data-depth')) || 0;
            const x = clamp(px * depth, -24, 24);
            const y = clamp(py * depth, -20, 20);
            el.style.transform = `translate3d(${x}px, ${y}px, 0)`;
          });
        };

        stage.addEventListener('mousemove', (event) => {
          const rect = stage.getBoundingClientRect();
          const x = event.clientX - rect.left;
          const y = event.clientY - rect.top;
          const px = (x / rect.width - 0.5) * 30;
          const py = (y / rect.height - 0.5) * 30;
          moveLayers(px, py);
        });

        const onScroll = () => {
          const rect = stage.getBoundingClientRect();
          const progress = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
          const amount = clamp((progress - 0.5) * 28, -14, 14);
          moveLayers(amount, amount * -0.65);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
      })();
    </script>
  @endpush
@endsection
