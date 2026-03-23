<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'LevelUp Life!')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;700;800&family=Manrope:wght@500;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg:#fffaf0;
      --bg-2:#f4fbff;
      --surface:#ffffff;
      --surface-2:#fff2fe;
      --text:#1f2744;
      --muted:#58638d;
      --line:#dbe6ff;
      --primary:#ff6b8a;
      --accent:#4f7cff;
      --mint:#14c8a8;
      --danger:#ef476f;
      --shadow:0 14px 35px rgba(90,109,170,.16);
    }

    *{ box-sizing:border-box; }
    html{ scroll-behavior:smooth; }
    body{
      position:relative;
      margin:0;
      color:var(--text);
      font-family:"Manrope", system-ui, -apple-system, Segoe UI, Roboto, Arial;
      background:
        radial-gradient(900px 600px at 12% -8%, rgba(255,107,138,.34), transparent 60%),
        radial-gradient(900px 600px at 90% 0%, rgba(79,124,255,.32), transparent 58%),
        linear-gradient(180deg, #fff7ec 0%, #f1f8ff 45%, #eef4ff 100%);
      min-height:100vh;
      overflow-x:hidden;
    }

    a{ color:inherit; text-decoration:none; }
    .wrap{
      position:relative;
      z-index:2;
      max-width:1160px;
      margin:0 auto;
      padding:0 22px;
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
      width:190px;
      height:190px;
      border-radius:56px;
      border:1px solid rgba(255,255,255,.54);
      background:
        radial-gradient(circle at 30% 28%, rgba(255,255,255,.55), transparent 40%),
        linear-gradient(135deg, rgba(255,107,138,.32), rgba(79,124,255,.32));
      box-shadow:
        inset 0 0 0 12px rgba(255,255,255,.15),
        0 16px 40px rgba(95,133,255,.2);
      opacity:.35;
      transform:translate3d(0,0,0);
      animation:floatLogo 7s ease-in-out infinite;
    }
    .bg-logo::before{
      content:"";
      position:absolute;
      inset:22%;
      border-radius:30px;
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
      font-size:50px;
      color:rgba(255,255,255,.52);
      letter-spacing:-2px;
    }
    .bg-logo.bg1{ top:10%; left:6%; animation-delay:-1.6s; }
    .bg-logo.bg2{ top:24%; right:8%; width:132px; height:132px; border-radius:38px; animation-delay:-3.1s; }
    .bg-logo.bg3{ bottom:8%; left:21%; width:154px; height:154px; border-radius:44px; animation-delay:-2.4s; }
    .bg-logo.bg4{ bottom:12%; right:14%; width:112px; height:112px; border-radius:34px; animation-delay:-4.2s; }

    .nav{
      position:sticky;
      top:0;
      z-index:70;
      background:rgba(255,255,255,.84);
      backdrop-filter: blur(8px);
      border-bottom:1px solid var(--line);
    }
    .navRow{
      height:74px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:14px;
    }
    .brand{
      display:flex;
      align-items:center;
      gap:10px;
      font-family:"Sora", system-ui, sans-serif;
      font-weight:800;
      font-size:15px;
      letter-spacing:-.2px;
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

    .navLinks{
      display:flex;
      gap:12px;
      color:var(--muted);
      font-size:13px;
      font-weight:700;
    }
    .navLinks a{
      padding:10px 12px;
      border-radius:11px;
      transition:.14s ease;
    }
    .navLinks a:hover{
      color:var(--text);
      background:#f2f6ff;
    }

    .actions{ display:flex; align-items:center; gap:10px; }
    .btn{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      border-radius:12px;
      border:1px solid var(--line);
      padding:10px 14px;
      background:#fff;
      color:var(--text);
      font-size:13px;
      font-weight:800;
      cursor:pointer;
      transition:.14s ease;
      box-shadow:0 6px 15px rgba(104,129,211,.08);
    }
    .btn:hover{
      transform:translateY(-1px);
      box-shadow:0 10px 22px rgba(104,129,211,.16);
      border-color:#cad9ff;
    }
    .btn.primary{
      color:#fff;
      border-color:transparent;
      background:linear-gradient(135deg, #ff6b8a, #5f85ff);
    }

    .section{ padding:44px 0; }
    .panel{
      border:1px solid var(--line);
      border-radius:24px;
      background:linear-gradient(180deg, #ffffff 0%, #fff8ff 100%);
      box-shadow:var(--shadow);
      padding:20px;
    }
    .cardTitle{
      margin:0;
      font-family:"Sora", system-ui, sans-serif;
      font-size:19px;
      font-weight:800;
      letter-spacing:-.3px;
    }
    .muted{ color:var(--muted); font-size:14px; line-height:1.65; }
    .hLabel{ font-size:11px; letter-spacing:.15em; text-transform:uppercase; color:#7a86b1; font-weight:800; }
    .hTitle{ margin:8px 0; font-family:"Sora", system-ui, sans-serif; font-size:31px; line-height:1.12; letter-spacing:-.4px; }
    .hText{ margin:0; color:var(--muted); line-height:1.72; font-size:14px; }

    .specs{ display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:14px; }
    .spec{
      border:1px solid var(--line);
      border-radius:18px;
      background:var(--surface);
      padding:14px;
    }
    .spec .k{ color:#7a86b1; font-size:11px; letter-spacing:.13em; text-transform:uppercase; font-weight:800; }
    .spec .v{ margin-top:8px; font-weight:800; font-size:14px; }
    .spec .d{ margin-top:6px; color:var(--muted); font-size:13px; line-height:1.6; }

    .formStack{ margin-top:14px; display:grid; gap:12px; }
    .fieldLabel{ display:block; margin-bottom:7px; font-size:13px; color:var(--muted); font-weight:800; }
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
    .textInput:focus{
      border-color:#90a9ff;
      box-shadow:0 0 0 4px rgba(95,133,255,.15);
    }
    .alert{
      border-radius:14px;
      border:1px solid #ffc0cc;
      background:#fff0f4;
      padding:12px;
    }

    [data-reveal]{ opacity:0; transform:translateY(14px); transition:opacity .45s ease, transform .45s ease; }
    .reveal-in{ opacity:1; transform:translateY(0); }

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
      border:1px solid #ffffff;
      border-radius:24px;
      background:rgba(255,255,255,.8);
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

    @keyframes runTrack{
      from{ left:-20%; }
      to{ left:100%; }
    }
    @keyframes loadSlide{
      from{ transform:translateX(-120%); }
      to{ transform:translateX(320%); }
    }
    @keyframes floatLogo{
      0%, 100%{ transform:translate3d(0,0,0) rotate(0deg); }
      50%{ transform:translate3d(0,-12px,0) rotate(3deg); }
    }
    @keyframes logoPulse{
      0%, 100%{ box-shadow:0 10px 24px rgba(95,133,255,.34), inset 0 0 0 1px rgba(255,255,255,.3); }
      50%{ box-shadow:0 14px 30px rgba(95,133,255,.46), inset 0 0 0 1px rgba(255,255,255,.4); }
    }

    @media (max-width: 980px){
      .navLinks{ display:none; }
      .specs{ grid-template-columns:1fr; }
      .hTitle{ font-size:26px; }
    }
  </style>

  @stack('styles')
  @stack('head')
</head>
<body>
  <div class="opening-screen" id="openingScreen">
    <div class="opening-card">
      <h2 class="opening-title">LevelUp Life!</h2>
      <p class="opening-sub">Warm up your habits. Build consistency.</p>
      <div class="runner-track"></div>
    </div>
  </div>

  <div class="route-loader" id="routeLoader">
    <div class="pulse"></div>
    <div class="fitness">Preparing your fitness journey...</div>
  </div>

  <div class="bg-parallax" id="bgParallax" aria-hidden="true">
    <span class="bg-logo bg1" data-depth="0.16"></span>
    <span class="bg-logo bg2" data-depth="0.28"></span>
    <span class="bg-logo bg3" data-depth="0.21"></span>
    <span class="bg-logo bg4" data-depth="0.34"></span>
  </div>

  <header class="nav">
    <div class="wrap navRow">
      <a class="brand" href="{{ url('/') }}">
        <span class="logo"></span>
        <span>LevelUp Life!</span>
      </a>

      <nav class="navLinks">
        <a href="{{ url('/') }}">Home</a>
        @if(session('user_id'))
          <a href="{{ url('/dashboard') }}">Dashboard</a>
        @endif
      </nav>

      <div class="actions">
        @if(session('user_id'))
          <a class="btn primary" href="{{ url('/dashboard') }}">Dashboard</a>
          <a class="btn" href="{{ url('/logout') }}">Logout</a>
        @else
          <a class="btn {{ request()->is('login') ? 'primary' : '' }}" href="{{ url('/login') }}">Login</a>
          <a class="btn {{ request()->is('register') ? 'primary' : '' }}" href="{{ url('/register') }}">Create account</a>
        @endif
      </div>
    </div>
  </header>

  @yield('content')

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

    const bgParallax = document.getElementById('bgParallax');
    const bgItems = bgParallax ? bgParallax.querySelectorAll('[data-depth]') : [];
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const clamp = (value, min, max) => Math.min(max, Math.max(min, value));
    const moveBackground = (x, y) => {
      bgItems.forEach((el) => {
        const depth = Number(el.getAttribute('data-depth')) || 0;
        const tx = clamp(x * depth, -34, 34);
        const ty = clamp(y * depth, -30, 30);
        el.style.transform = `translate3d(${tx}px, ${ty}px, 0)`;
      });
    };

    if (!reducedMotion && bgItems.length) {
      window.addEventListener('mousemove', (event) => {
        const px = (event.clientX / window.innerWidth - 0.5) * 36;
        const py = (event.clientY / window.innerHeight - 0.5) * 36;
        moveBackground(px, py);
      });

      const onScrollParallax = () => {
        const amount = clamp(window.scrollY * 0.03, -26, 26);
        moveBackground(amount, amount * -0.52);
      };
      onScrollParallax();
      window.addEventListener('scroll', onScrollParallax, { passive: true });
    }

    const revealTargets = document.querySelectorAll('[data-reveal]');
    if (revealTargets.length) {
      const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('reveal-in');
            io.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12 });
      revealTargets.forEach((el) => io.observe(el));
    }
  </script>
</body>
</html>
