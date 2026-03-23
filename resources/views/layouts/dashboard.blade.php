<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Dashboard')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;700;800&family=Manrope:wght@500;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg:#f7fbff;
      --bg-soft:#fff9f1;
      --surface:#ffffff;
      --surface-2:#fff4fd;
      --text:#1f2744;
      --muted:#5a668f;
      --line:#dbe6ff;
      --accent:#4f7cff;
      --primary:#ff6b8a;
      --success:#14c8a8;
      --danger:#ef476f;
      --shadow:0 14px 32px rgba(89,110,173,.14);
    }

    *{ box-sizing:border-box; }
    a{ color:inherit; text-decoration:none; }
    body{
      position:relative;
      margin:0;
      color:var(--text);
      font-family:"Manrope", system-ui, -apple-system, Segoe UI, Roboto, Arial;
      background:
        radial-gradient(900px 600px at 8% 0%, rgba(255,107,138,.32), transparent 58%),
        radial-gradient(760px 560px at 92% 4%, rgba(79,124,255,.3), transparent 60%),
        linear-gradient(180deg, #f8faff 0%, #fdf8ff 42%, #fff9f0 100%);
      min-height:100vh;
      overflow-x:hidden;
    }

    .bg-parallax{
      position:fixed;
      inset:0;
      z-index:0;
      pointer-events:none;
      overflow:hidden;
    }
    .bg-logo{
      position:absolute;
      width:170px;
      height:170px;
      border-radius:50px;
      border:1px solid rgba(255,255,255,.54);
      background:
        radial-gradient(circle at 30% 28%, rgba(255,255,255,.55), transparent 40%),
        linear-gradient(135deg, rgba(255,107,138,.29), rgba(79,124,255,.29));
      box-shadow:
        inset 0 0 0 12px rgba(255,255,255,.15),
        0 16px 40px rgba(95,133,255,.18);
      opacity:.26;
      transform:translate3d(0,0,0);
      animation:floatLogo 7.4s ease-in-out infinite;
    }
    .bg-logo::before{
      content:"";
      position:absolute;
      inset:22%;
      border-radius:26px;
      border:2px solid rgba(255,255,255,.4);
      transform:rotate(12deg);
    }
    .bg-logo::after{
      content:"L";
      position:absolute;
      inset:0;
      display:grid;
      place-items:center;
      font-family:"Sora", system-ui, sans-serif;
      font-weight:800;
      font-size:44px;
      color:rgba(255,255,255,.5);
      letter-spacing:-2px;
    }
    .bg-logo.bg1{ top:11%; left:16%; animation-delay:-1.4s; }
    .bg-logo.bg2{ top:24%; right:6%; width:124px; height:124px; border-radius:36px; animation-delay:-2.9s; }
    .bg-logo.bg3{ bottom:8%; left:34%; width:146px; height:146px; border-radius:42px; animation-delay:-3.6s; }
    .bg-logo.bg4{ bottom:14%; right:22%; width:108px; height:108px; border-radius:30px; animation-delay:-4.7s; }

    .shell{
      position:relative;
      z-index:2;
      display:grid;
      grid-template-columns: 260px 1fr;
      min-height:100vh;
    }

    .sidebar{
      background:rgba(255,255,255,.88);
      border-right:1px solid var(--line);
      padding:16px 12px;
      position:sticky;
      top:0;
      height:100vh;
      overflow-y:auto;
    }
    .brand{
      display:flex;
      gap:10px;
      align-items:center;
      border:1px solid var(--line);
      border-radius:14px;
      padding:10px;
      background:linear-gradient(135deg, #ffe8f0, #edf3ff);
    }
    .logo{
      position:relative;
      display:grid;
      place-items:center;
      width:38px;
      height:38px;
      border-radius:12px;
      border:1px solid rgba(255,255,255,.65);
      background:
        radial-gradient(circle at 24% 20%, #ffffffb3, transparent 42%),
        linear-gradient(140deg, #ff5f91 0%, #9a6bff 45%, #45a6ff 100%);
      box-shadow:
        0 10px 24px rgba(95,133,255,.34),
        inset 0 0 0 1px rgba(255,255,255,.3);
      animation:logoPulse 2.8s ease-in-out infinite;
    }
    .logo::before{
      content:"";
      position:absolute;
      width:18px;
      height:18px;
      border-radius:7px;
      border:2px solid rgba(255,255,255,.8);
      transform:rotate(10deg);
    }
    .logo::after{
      content:"";
      position:absolute;
      width:8px;
      height:13px;
      background:linear-gradient(180deg, #ffffff, #e5efff);
      clip-path:polygon(40% 0, 100% 0, 60% 46%, 90% 46%, 18% 100%, 40% 58%, 8% 58%);
      filter:drop-shadow(0 1px 2px rgba(20,34,86,.3));
    }
    .brand .t{ font-family:"Sora", system-ui, sans-serif; font-weight:800; font-size:14px; }
    .brand .s{ color:#6b76a2; font-size:11px; font-weight:700; margin-top:2px; }

    .nav{
      margin-top:12px;
      display:grid;
      gap:6px;
    }
    .nav a{
      display:flex;
      align-items:center;
      gap:10px;
      padding:10px 12px;
      border:1px solid transparent;
      border-radius:12px;
      color:var(--muted);
      font-weight:800;
      font-size:13px;
      transition:.14s ease;
    }
    .nav a:hover{
      transform:translateY(-1px);
      background:#f2f6ff;
      border-color:#d8e2ff;
      color:var(--text);
    }
    .nav a.active{
      color:#25305b;
      background:#edf2ff;
      border-color:#bdd0ff;
    }
    .dot{
      width:9px;
      height:9px;
      border-radius:999px;
      background:#ccd8ff;
      border:1px solid #b8c7f5;
    }
    .nav a.active .dot{
      background:#5f85ff;
      box-shadow:0 0 0 5px rgba(95,133,255,.18);
      border-color:#5f85ff;
    }

    .main{
      padding:18px 22px 28px;
      min-width:0;
    }
    .topbar{
      position:sticky;
      top:0;
      z-index:30;
      margin-bottom:16px;
      padding:10px 0;
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:12px;
      background:linear-gradient(180deg, rgba(247,251,255,.96), rgba(247,251,255,.84) 72%, rgba(247,251,255,0));
    }
    .pageTitle{
      font-family:"Sora", system-ui, sans-serif;
      font-size:20px;
      font-weight:800;
      letter-spacing:-.25px;
    }
    .pageSub{
      color:var(--muted);
      font-size:13px;
      margin-top:4px;
      font-weight:700;
    }

    .card{
      border:1px solid var(--line);
      border-radius:18px;
      background:linear-gradient(180deg, #ffffff, #fff8ff);
      box-shadow:var(--shadow);
      padding:16px;
    }
    .cardTitle{
      font-family:"Sora", system-ui, sans-serif;
      font-size:16px;
      font-weight:800;
      margin-bottom:8px;
    }
    .muted{ color:var(--muted); }
    .grid{ display:grid; grid-template-columns:1fr 1fr; gap:14px; }

    .btn{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      padding:10px 14px;
      border-radius:12px;
      border:1px solid var(--line);
      background:#fff;
      color:var(--text);
      font-weight:800;
      font-size:13px;
      cursor:pointer;
      transition:.14s ease;
    }
    .btn:hover{
      transform:translateY(-1px);
      border-color:#cad9ff;
      box-shadow:0 10px 20px rgba(103,127,199,.14);
    }
    .btn.primary{
      color:#fff;
      border-color:transparent;
      background:linear-gradient(135deg, #ff6b8a, #5f85ff);
    }
    .btn-success{
      color:#fff;
      border-color:transparent;
      background:linear-gradient(135deg, #14c8a8, #5f85ff);
    }
    .btn-danger{
      color:#fff;
      border-color:transparent;
      background:linear-gradient(135deg, #ef476f, #ff7a59);
    }
    .btn-muted{
      background:#f8faff;
      border-color:#cfdbff;
    }

    .input,
    .textInput{
      width:100%;
      padding:12px;
      border-radius:12px;
      border:1px solid var(--line);
      background:#fff;
      color:var(--text);
      font-weight:700;
      outline:none;
      transition:.13s ease;
    }
    .input:focus,
    .textInput:focus{
      border-color:#91a9ff;
      box-shadow:0 0 0 4px rgba(95,133,255,.15);
    }

    .habit-row{ animation:rowIn .4s ease both; animation-delay:var(--d, 0ms); }
    .habit-row:hover{ background:#f8fbff; }

    .btn.is-loading{ pointer-events:none; opacity:.85; }
    .btn.is-loading::before{
      content:"";
      width:12px;
      height:12px;
      border-radius:999px;
      border:2px solid rgba(255,255,255,.55);
      border-top-color:#fff;
      animation:spin .8s linear infinite;
    }

    .opening-screen{
      position:fixed;
      inset:0;
      z-index:120;
      display:flex;
      align-items:center;
      justify-content:center;
      background:linear-gradient(145deg, #ffe2ec, #e9f0ff);
      transition:opacity .45s ease, visibility .45s ease;
    }
    .opening-screen.hide{ opacity:0; visibility:hidden; }
    .opening-card{
      width:min(420px, 88vw);
      border:1px solid #fff;
      border-radius:24px;
      background:rgba(255,255,255,.84);
      box-shadow:0 18px 44px rgba(118,133,186,.25);
      padding:22px;
      text-align:center;
    }
    .runner-track{
      position:relative;
      height:14px;
      margin:12px auto 0;
      border-radius:999px;
      background:linear-gradient(90deg, #ffd0db, #cad9ff);
      overflow:hidden;
    }
    .runner-track::after{
      content:"";
      position:absolute;
      left:-20%;
      top:2px;
      width:20%;
      height:10px;
      border-radius:999px;
      background:#14c8a8;
      animation:runTrack 1.1s linear infinite;
    }
    .opening-title{ margin:0; font-family:"Sora", system-ui, sans-serif; font-size:24px; }
    .opening-sub{ margin:8px 0 0; color:#66719b; font-size:13px; font-weight:700; }

    .route-loader{
      position:fixed;
      inset:0;
      z-index:110;
      pointer-events:none;
      opacity:0;
      transition:opacity .2s ease;
      background:rgba(255,255,255,.55);
      backdrop-filter: blur(2px);
    }
    .route-loader.active{ opacity:1; }
    .route-loader .pulse{
      position:absolute;
      top:18px;
      left:50%;
      transform:translateX(-50%);
      width:min(320px, 72vw);
      height:8px;
      border-radius:999px;
      overflow:hidden;
      background:#f0f4ff;
      border:1px solid #d6e2ff;
    }
    .route-loader .pulse::after{
      content:"";
      display:block;
      width:40%;
      height:100%;
      background:linear-gradient(90deg, #14c8a8, #5f85ff, #ff6b8a);
      animation:loadSlide 1s linear infinite;
    }
    .route-loader .fitness{
      position:absolute;
      top:34px;
      left:50%;
      transform:translateX(-50%);
      color:#5d6a96;
      font-size:12px;
      font-weight:800;
    }

    @keyframes rowIn{ from{ opacity:0; transform:translateY(10px); } to{ opacity:1; transform:translateY(0); } }
    @keyframes spin{ to{ transform:rotate(360deg); } }
    @keyframes runTrack{ from{ left:-20%; } to{ left:100%; } }
    @keyframes loadSlide{ from{ transform:translateX(-120%); } to{ transform:translateX(320%); } }
    @keyframes floatLogo{
      0%, 100%{ transform:translate3d(0,0,0) rotate(0deg); }
      50%{ transform:translate3d(0,-10px,0) rotate(3deg); }
    }
    @keyframes logoPulse{
      0%, 100%{ box-shadow:0 10px 24px rgba(95,133,255,.34), inset 0 0 0 1px rgba(255,255,255,.3); }
      50%{ box-shadow:0 14px 30px rgba(95,133,255,.46), inset 0 0 0 1px rgba(255,255,255,.4); }
    }

    @media (max-width: 980px){
      .shell{ grid-template-columns:1fr; }
      .sidebar{
        position:relative;
        height:auto;
        border-right:none;
        border-bottom:1px solid var(--line);
      }
      .main{ padding:14px 14px 22px; }
      .grid{ grid-template-columns:1fr; }
    }
  </style>

  @stack('styles')
  @stack('head')
</head>
<body>
  <div class="opening-screen" id="openingScreen">
    <div class="opening-card">
      <h2 class="opening-title">LevelUp Life!</h2>
      <p class="opening-sub">Warm up your habits. Keep moving.</p>
      <div class="runner-track"></div>
    </div>
  </div>

  <div class="route-loader" id="routeLoader">
    <div class="pulse"></div>
    <div class="fitness">Loading your fitness dashboard...</div>
  </div>

  <div class="bg-parallax" id="bgParallax" aria-hidden="true">
    <span class="bg-logo bg1" data-depth="0.14"></span>
    <span class="bg-logo bg2" data-depth="0.3"></span>
    <span class="bg-logo bg3" data-depth="0.22"></span>
    <span class="bg-logo bg4" data-depth="0.33"></span>
  </div>

  <div class="shell">
    <aside class="sidebar">
      <div class="brand">
        <div class="logo"></div>
        <div>
          <div class="t">LevelUp Life!</div>
          <div class="s">Daily execution</div>
        </div>
      </div>

      <nav class="nav">
        <a href="{{ url('/dashboard') }}" class="@yield('nav_dashboard')"><span class="dot"></span>Dashboard</a>
        <a href="{{ url('/add-habit') }}" class="@yield('nav_habits')"><span class="dot"></span>Habits</a>
        <a href="{{ url('/friends') }}" class="@yield('nav_friends')"><span class="dot"></span>Friends</a>
        <a href="{{ url('/leaderboard') }}" class="@yield('nav_leaderboard')"><span class="dot"></span>Leaderboard</a>
        <a href="{{ url('/feed') }}" class="@yield('nav_feed')"><span class="dot"></span>Feed</a>
        <a href="{{ url('/profile') }}" class="@yield('nav_profile')"><span class="dot"></span>Profile</a>
        @if(session('is_super_admin'))
          <a href="{{ url('/admin') }}" class="@yield('nav_admin')"><span class="dot"></span>Admin</a>
        @endif
        <a href="{{ url('/logout') }}"><span class="dot"></span>Logout</a>
      </nav>
    </aside>

    <main class="main">
      <div class="topbar">
        <div>
          <div class="pageTitle">@yield('page_title', 'Dashboard')</div>
          <div class="pageSub">@yield('page_subtitle', 'Overview')</div>
        </div>
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
          @yield('top_actions')
        </div>
      </div>

      @yield('content')
    </main>
  </div>

  @stack('scripts')
  <script>
    const openingScreen = document.getElementById('openingScreen');
    const routeLoader = document.getElementById('routeLoader');
    window.addEventListener('load', () => {
      setTimeout(() => openingScreen?.classList.add('hide'), 850);
    });

    const showLoader = () => routeLoader?.classList.add('active');
    document.addEventListener('click', (e) => {
      const link = e.target.closest('a[href]');
      if (!link) return;
      const href = link.getAttribute('href') || '';
      if (href.startsWith('#') || href.startsWith('javascript:')) return;
      showLoader();
    });
    document.addEventListener('submit', () => showLoader());

    document.addEventListener('click', function(e){
      const btn = e.target.closest('button.btn-success[data-loading="true"]');
      if (!btn) return;
      btn.classList.add('is-loading');
      btn.textContent = 'Saving';
    });

    const bgParallax = document.getElementById('bgParallax');
    const bgItems = bgParallax ? bgParallax.querySelectorAll('[data-depth]') : [];
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const clamp = (value, min, max) => Math.min(max, Math.max(min, value));
    const moveBackground = (x, y) => {
      bgItems.forEach((el) => {
        const depth = Number(el.getAttribute('data-depth')) || 0;
        const tx = clamp(x * depth, -30, 30);
        const ty = clamp(y * depth, -26, 26);
        el.style.transform = `translate3d(${tx}px, ${ty}px, 0)`;
      });
    };

    if (!reducedMotion && bgItems.length) {
      window.addEventListener('mousemove', (event) => {
        const px = (event.clientX / window.innerWidth - 0.5) * 30;
        const py = (event.clientY / window.innerHeight - 0.5) * 30;
        moveBackground(px, py);
      });

      const onScrollParallax = () => {
        const amount = clamp(window.scrollY * 0.028, -22, 22);
        moveBackground(amount, amount * -0.48);
      };
      onScrollParallax();
      window.addEventListener('scroll', onScrollParallax, { passive: true });
    }
  </script>
</body>
</html>
