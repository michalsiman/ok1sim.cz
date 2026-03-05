<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MeshCore — Připoj se k síti</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@300;400;500&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #060a10;
    --surface: #0d1520;
    --surface2: #111d2e;
    --border: rgba(0,200,160,0.15);
    --accent: #00c8a0;
    --accent2: #0090ff;
    --accent3: #7b5ea7;
    --text: #e8f0fe;
    --text-muted: #6b80a0;
    --glow: 0 0 40px rgba(0,200,160,0.15);
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  html { scroll-behavior: smooth; }

  body {
    background: var(--bg);
    color: var(--text);
    font-family: 'Outfit', sans-serif;
    font-weight: 300;
    line-height: 1.7;
    overflow-x: hidden;
  }

  /* ─── NOISE OVERLAY ─── */
  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    pointer-events: none;
    z-index: 9999;
    opacity: 0.5;
  }

  /* ─── BACKGROUND MESH BLOBS ─── */
  .bg-blobs {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
  }
  .blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.12;
  }
  .blob-1 { width: 600px; height: 600px; background: var(--accent); top: -200px; left: -200px; animation: drift 20s ease-in-out infinite alternate; }
  .blob-2 { width: 500px; height: 500px; background: var(--accent2); bottom: 20%; right: -150px; animation: drift 25s ease-in-out infinite alternate-reverse; }
  .blob-3 { width: 400px; height: 400px; background: var(--accent3); top: 50%; left: 40%; animation: drift 18s ease-in-out infinite alternate; }

  @keyframes drift {
    from { transform: translate(0, 0) scale(1); }
    to { transform: translate(60px, 40px) scale(1.1); }
  }

  /* ─── LAYOUT ─── */
  .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; position: relative; z-index: 1; }

  /* ─── NAV ─── */
  nav {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 100;
    padding: 18px 0;
    background: rgba(6,10,16,0.8);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
  }
  nav .container { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
  .nav-logo {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800;
    font-size: 1.3rem;
    letter-spacing: -0.03em;
    color: var(--text);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .logo-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    background: var(--accent);
    box-shadow: 0 0 12px var(--accent);
    animation: pulse 2s ease-in-out infinite;
  }
  @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.6;transform:scale(0.8)} }

  .nav-links { display: flex; gap: 8px; flex-wrap: wrap; }
  .nav-links a {
    font-family: 'DM Mono', monospace;
    font-size: 0.72rem;
    color: var(--text-muted);
    text-decoration: none;
    padding: 6px 14px;
    border: 1px solid transparent;
    border-radius: 100px;
    transition: all 0.2s;
    letter-spacing: 0.05em;
    text-transform: uppercase;
  }
  .nav-links a:hover {
    color: var(--accent);
    border-color: var(--border);
    background: rgba(0,200,160,0.05);
  }
  .nav-cta {
    background: var(--accent) !important;
    color: var(--bg) !important;
    font-weight: 500 !important;
    border-color: transparent !important;
  }
  .nav-cta:hover { background: #00e8b8 !important; transform: translateY(-1px); }

  /* ─── HERO ─── */
  .hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 120px 0 80px;
  }
  .hero-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }
  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'DM Mono', monospace;
    font-size: 0.72rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--accent);
    background: rgba(0,200,160,0.08);
    border: 1px solid rgba(0,200,160,0.25);
    padding: 6px 16px;
    border-radius: 100px;
    margin-bottom: 28px;
  }
  .hero-badge span { width: 6px; height: 6px; border-radius: 50%; background: var(--accent); animation: pulse 1.5s infinite; }

  .hero h1 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2.8rem, 6vw, 5rem);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -0.03em;
    margin-bottom: 24px;
  }
  .hero h1 em {
    font-style: normal;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hero-desc {
    font-size: 1.1rem;
    color: var(--text-muted);
    max-width: 460px;
    margin-bottom: 40px;
    line-height: 1.8;
  }

  .hero-actions { display: flex; gap: 16px; flex-wrap: wrap; }
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    border-radius: 12px;
    font-family: 'Outfit', sans-serif;
    font-weight: 500;
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.25s;
    cursor: pointer;
    border: none;
  }
  .btn-primary {
    background: var(--accent);
    color: var(--bg);
  }
  .btn-primary:hover { background: #00e8b8; transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,200,160,0.3); }
  .btn-ghost {
    background: transparent;
    color: var(--text);
    border: 1px solid var(--border);
  }
  .btn-ghost:hover { border-color: var(--accent); color: var(--accent); transform: translateY(-2px); }

  /* ─── MESH VISUALIZER ─── */
  .mesh-visual {
    position: relative;
    height: 420px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .mesh-canvas { width: 100%; height: 100%; }

  /* ─── SECTION BASE ─── */
  section { padding: 100px 0; position: relative; z-index: 1; }
  .section-label {
    font-family: 'DM Mono', monospace;
    font-size: 0.7rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .section-label::before {
    content: '';
    display: block;
    width: 30px;
    height: 1px;
    background: var(--accent);
  }
  .section-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800;
    letter-spacing: -0.03em;
    line-height: 1.1;
    margin-bottom: 16px;
  }
  .section-desc { color: var(--text-muted); font-size: 1.05rem; max-width: 580px; margin-bottom: 60px; }

  /* ─── STEPS ─── */
  .steps { display: flex; flex-direction: column; gap: 2px; }
  .step {
    display: grid;
    grid-template-columns: 80px 1fr;
    gap: 32px;
    align-items: start;
    padding: 32px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
  }
  .step::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, var(--accent), transparent);
    opacity: 0;
    transition: opacity 0.3s;
  }
  .step:hover { border-color: rgba(0,200,160,0.3); transform: translateX(4px); }
  .step:hover::before { opacity: 1; }

  .step-num {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 3rem;
    font-weight: 800;
    color: var(--border);
    line-height: 1;
    transition: color 0.3s;
  }
  .step:hover .step-num { color: rgba(0,200,160,0.3); }
  .step-content h3 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--text);
  }
  .step-content p { color: var(--text-muted); font-size: 0.95rem; }
  .step-tags { display: flex; gap: 8px; margin-top: 14px; flex-wrap: wrap; }
  .tag {
    font-family: 'DM Mono', monospace;
    font-size: 0.65rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 6px;
    background: rgba(0,144,255,0.1);
    color: var(--accent2);
    border: 1px solid rgba(0,144,255,0.2);
  }
  .tag.green { background: rgba(0,200,160,0.08); color: var(--accent); border-color: rgba(0,200,160,0.2); }
  .tag.purple { background: rgba(123,94,167,0.1); color: #b09ccc; border-color: rgba(123,94,167,0.2); }

  /* ─── CARDS GRID ─── */
  .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 32px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s;
  }
  .card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--accent), transparent);
    opacity: 0;
    transition: opacity 0.3s;
  }
  .card:hover { border-color: rgba(0,200,160,0.3); transform: translateY(-4px); box-shadow: var(--glow); }
  .card:hover::after { opacity: 1; }
  .card-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 20px;
    background: rgba(0,200,160,0.08);
    border: 1px solid rgba(0,200,160,0.15);
  }
  .card h3 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 10px;
  }
  .card p { color: var(--text-muted); font-size: 0.9rem; line-height: 1.7; }

  /* ─── CODE BLOCK ─── */
  .code-block {
    background: #080e18;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    margin: 24px 0;
  }
  .code-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-bottom: 1px solid var(--border);
    background: rgba(255,255,255,0.02);
  }
  .code-dots { display: flex; gap: 6px; }
  .code-dots span {
    width: 10px; height: 10px;
    border-radius: 50%;
  }
  .code-dots span:nth-child(1) { background: #ff5f57; }
  .code-dots span:nth-child(2) { background: #febc2e; }
  .code-dots span:nth-child(3) { background: #28c840; }
  .code-title { font-family: 'DM Mono', monospace; font-size: 0.72rem; color: var(--text-muted); letter-spacing: 0.05em; }
  pre {
    padding: 24px;
    font-family: 'DM Mono', monospace;
    font-size: 0.82rem;
    color: #8fbcbb;
    line-height: 1.8;
    overflow-x: auto;
  }
  pre .kw { color: var(--accent2); }
  pre .val { color: var(--accent); }
  pre .cm { color: var(--text-muted); font-style: italic; }
  pre .str { color: #a3be8c; }

  /* ─── TWO-COL ─── */
  .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: start; }
  .two-col.flip .col-text { order: 2; }
  .two-col.flip .col-visual { order: 1; }

  /* ─── PHONE MOCKUP ─── */
  .phone-mockup {
    width: 260px;
    height: 520px;
    border-radius: 40px;
    background: var(--surface2);
    border: 2px solid var(--border);
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 40px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);
    position: relative;
  }
  .phone-notch {
    width: 120px; height: 28px;
    background: var(--bg);
    border-radius: 0 0 20px 20px;
    margin: 0 auto;
    display: flex; align-items: center; justify-content: center;
    gap: 8px;
  }
  .phone-notch-cam { width: 10px; height: 10px; border-radius: 50%; background: #1a2535; border: 1px solid #2a3545; }
  .phone-screen {
    flex: 1;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    overflow: hidden;
  }
  .phone-topbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 4px 0;
  }
  .phone-topbar span { font-family: 'DM Mono', monospace; font-size: 0.65rem; color: var(--text-muted); }
  .phone-signal { display: flex; gap: 2px; align-items: flex-end; }
  .phone-signal span { width: 4px; border-radius: 2px; background: var(--accent); }
  .phone-signal span:nth-child(1) { height: 6px; }
  .phone-signal span:nth-child(2) { height: 10px; }
  .phone-signal span:nth-child(3) { height: 14px; }
  .phone-signal span:nth-child(4) { height: 18px; opacity: 0.4; }

  .msg-bubble {
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 0.78rem;
    max-width: 85%;
    line-height: 1.5;
  }
  .msg-in { background: var(--surface); border: 1px solid var(--border); align-self: flex-start; color: var(--text); }
  .msg-out { background: linear-gradient(135deg, var(--accent), #00a885); color: var(--bg); align-self: flex-end; font-weight: 500; }
  .msg-system {
    text-align: center;
    font-family: 'DM Mono', monospace;
    font-size: 0.62rem;
    color: var(--text-muted);
    padding: 4px;
  }
  .node-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(0,200,160,0.1); border: 1px solid rgba(0,200,160,0.2);
    border-radius: 8px; padding: 6px 10px;
    font-family: 'DM Mono', monospace; font-size: 0.68rem; color: var(--accent);
  }
  .node-pill-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--accent); animation: pulse 1.5s infinite; }

  /* ─── HARDWARE GRID ─── */
  .hw-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
  .hw-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    transition: all 0.3s;
    cursor: pointer;
  }
  .hw-card:hover { border-color: rgba(0,200,160,0.4); box-shadow: 0 0 30px rgba(0,200,160,0.1); }
  .hw-icon { font-size: 2rem; margin-bottom: 12px; }
  .hw-card h4 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 1rem; margin-bottom: 6px; }
  .hw-card p { font-size: 0.82rem; color: var(--text-muted); }
  .hw-badge {
    display: inline-block;
    margin-top: 12px;
    font-family: 'DM Mono', monospace;
    font-size: 0.62rem;
    padding: 3px 8px;
    border-radius: 6px;
    background: rgba(0,200,160,0.08);
    color: var(--accent);
    border: 1px solid rgba(0,200,160,0.2);
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  /* ─── DIVIDER ─── */
  .divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border), transparent);
    margin: 0;
  }

  /* ─── FAQ ACCORDION ─── */
  .faq { display: flex; flex-direction: column; gap: 8px; }
  .faq-item {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
  }
  .faq-q {
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.95rem;
    transition: color 0.2s;
    user-select: none;
  }
  .faq-q:hover { color: var(--accent); }
  .faq-arrow { transition: transform 0.3s; color: var(--text-muted); font-size: 1.2rem; }
  .faq-item.open .faq-arrow { transform: rotate(45deg); color: var(--accent); }
  .faq-a {
    padding: 0 24px;
    max-height: 0;
    overflow: hidden;
    transition: all 0.35s ease;
    color: var(--text-muted);
    font-size: 0.92rem;
  }
  .faq-item.open .faq-a { max-height: 300px; padding: 0 24px 20px; }

  /* ─── FOOTER ─── */
  footer {
    padding: 60px 0 40px;
    border-top: 1px solid var(--border);
    position: relative;
    z-index: 1;
  }
  .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 60px; margin-bottom: 48px; }
  .footer-brand p { color: var(--text-muted); font-size: 0.9rem; margin-top: 12px; max-width: 300px; }
  .footer-col h5 {
    font-family: 'DM Mono', monospace;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--accent);
    margin-bottom: 16px;
  }
  .footer-col a {
    display: block;
    color: var(--text-muted);
    text-decoration: none;
    font-size: 0.9rem;
    padding: 4px 0;
    transition: color 0.2s;
  }
  .footer-col a:hover { color: var(--text); }
  .footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 24px;
    border-top: 1px solid var(--border);
    font-family: 'DM Mono', monospace;
    font-size: 0.72rem;
    color: var(--text-muted);
  }

  /* ─── SCROLL ANIMATIONS ─── */
  .fade-up { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
  .fade-up.visible { opacity: 1; transform: translateY(0); }
  .stagger > * { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
  .stagger.visible > *:nth-child(1) { opacity: 1; transform: translateY(0); transition-delay: 0.1s; }
  .stagger.visible > *:nth-child(2) { opacity: 1; transform: translateY(0); transition-delay: 0.2s; }
  .stagger.visible > *:nth-child(3) { opacity: 1; transform: translateY(0); transition-delay: 0.3s; }
  .stagger.visible > *:nth-child(4) { opacity: 1; transform: translateY(0); transition-delay: 0.4s; }
  .stagger.visible > *:nth-child(5) { opacity: 1; transform: translateY(0); transition-delay: 0.5s; }
  .stagger.visible > *:nth-child(6) { opacity: 1; transform: translateY(0); transition-delay: 0.6s; }

  /* ─── STATS BAR ─── */
  .stats-bar {
    display: flex;
    gap: 0;
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 80px;
    background: var(--surface);
  }
  .stat {
    flex: 1;
    padding: 32px 24px;
    text-align: center;
    border-right: 1px solid var(--border);
    position: relative;
  }
  .stat:last-child { border-right: none; }
  .stat-num {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 2.2rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
    margin-bottom: 8px;
  }
  .stat-label { font-size: 0.82rem; color: var(--text-muted); }

  /* ─── HIGHLIGHT BOX ─── */
  .highlight-box {
    background: linear-gradient(135deg, rgba(0,200,160,0.06), rgba(0,144,255,0.06));
    border: 1px solid rgba(0,200,160,0.2);
    border-radius: 20px;
    padding: 40px;
    margin: 40px 0;
    position: relative;
    overflow: hidden;
  }
  .highlight-box::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(0,200,160,0.15), transparent);
    border-radius: 50%;
    transform: translate(-50%, -50%);
  }

  /* ─── MOBILE NAV TOGGLE ─── */
  .nav-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    padding: 4px;
  }
  .nav-toggle span { width: 22px; height: 2px; background: var(--text); border-radius: 2px; transition: all 0.3s; }

  /* ─── MOBILE ─── */
  @media (max-width: 768px) {
    .nav-links { display: none; position: absolute; top: 100%; left: 0; right: 0; flex-direction: column; background: var(--surface); border-bottom: 1px solid var(--border); padding: 16px 24px; gap: 4px; }
    .nav-links.open { display: flex; }
    .nav-toggle { display: flex; }
    .nav-cta { display: none; }
    .hero-grid { grid-template-columns: 1fr; }
    .mesh-visual { height: 280px; }
    .two-col { grid-template-columns: 1fr; }
    .two-col.flip .col-text { order: 1; }
    .two-col.flip .col-visual { order: 2; }
    .footer-grid { grid-template-columns: 1fr; gap: 32px; }
    .stats-bar { flex-direction: column; }
    .stat { border-right: none; border-bottom: 1px solid var(--border); }
    .stat:last-child { border-bottom: none; }
    .step { grid-template-columns: 50px 1fr; gap: 20px; }
    section { padding: 70px 0; }
  }
</style>
</head>
<body>

<div class="bg-blobs">
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>
  <div class="blob blob-3"></div>
</div>

<!-- NAV -->
<nav>
  <div class="container">
    <a href="#" class="nav-logo">
      <div class="logo-dot"></div>
      MeshCore<span style="color:var(--accent)">.</span>cz
    </a>
    <div class="nav-links" id="navLinks">
      <a href="#sit">O síti</a>
      <a href="#pripojeni">Připojení</a>
      <a href="#repeater">Repeater</a>
      <a href="#mobil">Mobil</a>
      <a href="#companion">Companion</a>
      <a href="#faq">FAQ</a>
      <a href="#zapoj-se" class="nav-cta">Zapoj se →</a>
    </div>
    <div class="nav-toggle" id="navToggle">
      <span></span><span></span><span></span>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero" id="uvod">
  <div class="container">
    <div class="hero-grid">
      <div class="hero-text">
        <div class="hero-badge"><span></span> Decentralizovaná síť · Funguje bez internetu</div>
        <h1>Připoj se<br>k <em>MeshCore</em><br>síti</h1>
        <p class="hero-desc">
          MeshCore je otevřená, decentralizovaná mesh síť postavená na LoRa rádiích.
          Komunikuj s ostatními uživateli i bez internetu, mobilního signálu nebo jakékoli infrastruktury.
          Stačí zařízení, aplikace a chuť experimentovat.
        </p>
        <div class="hero-actions">
          <a href="#pripojeni" class="btn btn-primary">Začít hned →</a>
          <a href="#sit" class="btn btn-ghost">Co je MeshCore?</a>
        </div>
      </div>
      <div class="mesh-visual">
        <canvas class="mesh-canvas" id="meshCanvas"></canvas>
      </div>
    </div>
  </div>
</section>

<div class="divider"></div>

<!-- STATS -->
<section id="sit" style="padding-top: 80px; padding-bottom: 40px;">
  <div class="container">
    <div class="stats-bar stagger">
      <div class="stat">
        <div class="stat-num">LoRa</div>
        <div class="stat-label">rádiový protokol</div>
      </div>
      <div class="stat">
        <div class="stat-num">868 MHz</div>
        <div class="stat-label">pásmo v Evropě</div>
      </div>
      <div class="stat">
        <div class="stat-num">~10 km</div>
        <div class="stat-label">dosah uzlu</div>
      </div>
      <div class="stat">
        <div class="stat-num">∞</div>
        <div class="stat-label">bez internetu</div>
      </div>
    </div>
    <!-- ABOUT -->
    <div class="two-col fade-up">
      <div class="col-text">
        <div class="section-label">Co je MeshCore</div>
        <h2 class="section-title">Síť,<br>která přežije<br>výpadek.</h2>
        <p style="color: var(--text-muted); margin-bottom: 20px;">
          MeshCore je firmware pro LoRa zařízení, který vytváří decentralizovanou mesh síť.
          Každý uzel je zároveň routerem — zprávy se šíří od uzlu k uzlu, obcházejí překážky a zvyšují dosah sítě.
        </p>
        <p style="color: var(--text-muted); margin-bottom: 28px;">
          Síť funguje nezávisle na internetu, mobilních operátorech nebo serverech.
          Je ideální pro outdoor aktivity, nouzové situace, komunitní komunikaci nebo jen pro radost z experimentování.
        </p>
        <div class="step-tags">
          <span class="tag green">Open source</span>
          <span class="tag">LoRa / LoRaWAN</span>
          <span class="tag purple">E2E šifrování</span>
          <span class="tag green">Bez SIM karty</span>
        </div>
      </div>
      <div class="col-visual">
        <div class="highlight-box">
          <div class="section-label" style="margin-bottom: 20px;">Jak funguje mesh</div>
          <p style="color: var(--text-muted); font-size: 0.92rem; margin-bottom: 24px;">
            Zpráva od tebe → putuje přes sousední uzly → dorazí k příjemci,
            i když na sebe přímo „nevidíte". Síť se sama organizuje a hledá nejlepší cestu.
          </p>
          <div style="display: flex; flex-direction: column; gap: 10px;">
            <div class="node-pill"><div class="node-pill-dot"></div> Tvůj uzel — OK1XYZ</div>
            <div style="padding-left: 20px; color: var(--text-muted); font-family: 'DM Mono', monospace; font-size: 0.75rem;">↓ přeposlání přes repeater</div>
            <div class="node-pill"><div class="node-pill-dot"></div> Repeater — Praha Žižkov (3 skoky)</div>
            <div style="padding-left: 20px; color: var(--text-muted); font-family: 'DM Mono', monospace; font-size: 0.75rem;">↓ doručeno</div>
            <div class="node-pill"><div class="node-pill-dot"></div> Příjemce — OK2ABC</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="divider"></div>

<!-- HARDWARE -->
<section>
  <div class="container">
    <div class="fade-up">
      <div class="section-label">Hardware</div>
      <h2 class="section-title">Co budeš potřebovat</h2>
      <p class="section-desc">MeshCore běží na dostupném LoRa hardware. Stačí jedno zařízení a jsi v síti.</p>
    </div>
    <div class="hw-grid stagger">
      <div class="hw-card">
        <div class="hw-icon">📡</div>
        <h4>Heltec V3</h4>
        <p>Populární výběr. WiFi + BLE + LoRa v jednom, integrovaný displej. Skvělý pro začátečníky.</p>
        <span class="hw-badge">Doporučeno</span>
      </div>
      <div class="hw-card">
        <div class="hw-icon">🔲</div>
        <h4>LILYGO T-Beam</h4>
        <p>GPS + LoRa + ESP32. Ideální pro mobilní uzly a sledování polohy. Má integrovanou baterii.</p>
        <span class="hw-badge">GPS tracker</span>
      </div>
      <div class="hw-card">
        <div class="hw-icon">⚡</div>
        <h4>RAK WisBlock</h4>
        <p>Modulární platforma. Lze sestavit přesně to, co potřebuješ. Nižší spotřeba energie.</p>
        <span class="hw-badge">Modulární</span>
      </div>
      <div class="hw-card">
        <div class="hw-icon">🖥️</div>
        <h4>ESP32 + SX1276</h4>
        <p>DIY řešení pro kutily. Kombinace vlastního ESP32 s LoRa modulem. Nejvíce flexibilní.</p>
        <span class="hw-badge">DIY</span>
      </div>
    </div>
  </div>
</section>

<div class="divider"></div>

<!-- PŘIPOJENÍ -->
<section id="pripojeni">
  <div class="container">
    <div class="fade-up">
      <div class="section-label">Krok za krokem</div>
      <h2 class="section-title">Jak se připojit<br>do sítě</h2>
      <p class="section-desc">Celý proces od nuly k funkčnímu uzlu zvládneš za odpoledne.</p>
    </div>
    <div class="steps stagger">
      <div class="step">
        <div class="step-num">01</div>
        <div class="step-content">
          <h3>Pořídit hardware</h3>
          <p>Vyber si kompatibilní LoRa zařízení z výše uvedeného přehledu. Nejsnazší start je s Heltec V3 nebo LILYGO T-Beam — dostupné online za cca 400–800 Kč.</p>
          <div class="step-tags"><span class="tag green">Hardware</span></div>
        </div>
      </div>
      <div class="step">
        <div class="step-num">02</div>
        <div class="step-content">
          <h3>Flashnout MeshCore firmware</h3>
          <p>Stáhni aktuální firmware z oficiálního repozitáře. Flashování přes webový flasher — stačí Chrome, USB kabel a jedno kliknutí. Žádný speciální software není potřeba.</p>
          <div class="step-tags"><span class="tag">Firmware</span><span class="tag green">Web Flasher</span></div>
          <div class="code-block" style="margin-top: 16px;">
            <div class="code-header">
              <div class="code-dots"><span></span><span></span><span></span></div>
              <div class="code-title">flasher.meshcore.network</div>
            </div>
            <pre><span class="cm"># Alternativně přes esptool.py</span>
esptool.py <span class="kw">--port</span> /dev/ttyUSB0 \
  <span class="kw">write_flash</span> <span class="val">0x0</span> <span class="str">meshcore-heltec-v3.bin</span></pre>
          </div>
        </div>
      </div>
      <div class="step">
        <div class="step-num">03</div>
        <div class="step-content">
          <h3>Prvotní konfigurace</h3>
          <p>Po flashování se připoj k zařízení přes BLE nebo sériový port. Nastav svůj callsign (nebo přezdívku), region (EU_868), výkon vysílání a kanál. Základní nastavení je hotové za 2 minuty.</p>
          <div class="step-tags"><span class="tag purple">BLE</span><span class="tag">EU 868 MHz</span></div>
        </div>
      </div>
      <div class="step">
        <div class="step-num">04</div>
        <div class="step-content">
          <h3>Připojit se ke kanálu komunity</h3>
          <p>Importuj QR kód nebo URL kanálu české komunity MeshCore. Okamžitě začneš vidět ostatní uzly v dosahu a můžeš psát zprávy.</p>
          <div class="step-tags"><span class="tag green">Komunita</span><span class="tag">QR kód</span></div>
        </div>
      </div>
      <div class="step">
        <div class="step-num">05</div>
        <div class="step-content">
          <h3>Připojit anténu a vyrazit</h3>
          <p>Pro maximální dosah použij externí anténu (SMA konektor). Ideální umístění je na okně, balkóně nebo střeše. Čím výše, tím lepší pokrytí pro ostatní!</p>
          <div class="step-tags"><span class="tag green">Anténa</span><span class="tag">Optimalizace</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="divider"></div>

<!-- REPEATER -->
<section id="repeater">
  <div class="container">
    <div class="two-col fade-up">
      <div class="col-text">
        <div class="section-label">Infrastruktura sítě</div>
        <h2 class="section-title">Vytvořit vlastní repeater</h2>
        <p style="color: var(--text-muted); margin-bottom: 24px;">
          Repeater je uzel, který přeposílá zprávy dál a rozšiřuje dosah sítě.
          Ideálně ho umísti na vyvýšené místo — střecha, stožár, kopec.
          Jeden dobře umístěný repeater pokryje celé město.
        </p>
        <p style="color: var(--text-muted); margin-bottom: 32px;">
          Doplňující text — zde popiš požadavky na hardware, napájení (solár, PoE, 230V),
          konfiguraci v aplikaci (router mode), a tipy pro umístění.
        </p>
        <div class="steps" style="gap: 8px;">
          <div class="step" style="padding: 20px 24px;">
            <div class="step-num" style="font-size: 1.8rem;">①</div>
            <div class="step-content">
              <h3>Nastavit Router Mode</h3>
              <p>V konfiguraci přepni zařízení do módu "Router/Repeater". Zařízení začne automaticky přeposílat zprávy.</p>
            </div>
          </div>
          <div class="step" style="padding: 20px 24px;">
            <div class="step-num" style="font-size: 1.8rem;">②</div>
            <div class="step-content">
              <h3>Umístění a napájení</h3>
              <p>Ideální: střecha budovy, 5V/1A napájení, venkovní anténa (fiberglass 5dBi). Spotřeba jen ~80 mA.</p>
            </div>
          </div>
          <div class="step" style="padding: 20px 24px;">
            <div class="step-num" style="font-size: 1.8rem;">③</div>
            <div class="step-content">
              <h3>Zaregistrovat na mapě</h3>
              <p>Přidej svůj repeater na komunitní mapu, ať ostatní vědí o pokrytí ve tvé oblasti.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-visual">
        <div class="code-block">
          <div class="code-header">
            <div class="code-dots"><span></span><span></span><span></span></div>
            <div class="code-title">Konfigurace repeateru</div>
          </div>
          <pre><span class="cm"># Základní nastavení repeateru</span>

<span class="kw">node_name</span> = <span class="str">"Praha-Zizkov-01"</span>
<span class="kw">mode</span> = <span class="val">ROUTER</span>
<span class="kw">region</span> = <span class="val">EU_868</span>
<span class="kw">frequency</span> = <span class="val">868.125</span> <span class="cm"># MHz</span>
<span class="kw">tx_power</span> = <span class="val">22</span>      <span class="cm"># dBm (max. legální)</span>
<span class="kw">hop_limit</span> = <span class="val">3</span>
<span class="kw">channel</span> = <span class="str">"meshcore-cz"</span>

<span class="cm"># Anténa</span>
<span class="kw">antenna_gain</span> = <span class="val">5</span>   <span class="cm"># dBi fiberglass</span>
<span class="kw">antenna_type</span> = <span class="str">"OMNI"</span></pre>
        </div>
        <div class="highlight-box" style="margin-top: 0;">
          <p style="font-size: 0.88rem; color: var(--text-muted);">
            💡 <strong style="color: var(--text);">Tip:</strong> Jeden repeater ve výšce 30 m nad okolím
            pokryje plochu s poloměrem 5–15 km v závislosti na terénu.
            Pro solární napájení stačí panel 5W + baterie 3Ah.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="divider"></div>

<!-- MOBIL -->
<section id="mobil">
  <div class="container">
    <div class="two-col flip fade-up">
      <div class="col-text">
        <div class="section-label">Mobilní aplikace</div>
        <h2 class="section-title">MeshCore na telefonu</h2>
        <p style="color: var(--text-muted); margin-bottom: 24px;">
          Oficiální aplikace MeshCore je dostupná pro Android a iOS.
          Připoj se k uzlu přes Bluetooth a komunikuj s celou sítí přímo z mobilu.
        </p>
        <div class="cards" style="grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 28px;">
          <div class="card" style="padding: 20px;">
            <div class="card-icon" style="font-size: 1.2rem; width: 40px; height: 40px; margin-bottom: 12px;">📱</div>
            <h3 style="font-size: 1rem;">Android</h3>
            <p style="font-size: 0.82rem;">Google Play + přímý APK download</p>
          </div>
          <div class="card" style="padding: 20px;">
            <div class="card-icon" style="font-size: 1.2rem; width: 40px; height: 40px; margin-bottom: 12px;">🍎</div>
            <h3 style="font-size: 1rem;">iOS</h3>
            <p style="font-size: 0.82rem;">App Store, vyžaduje iOS 14+</p>
          </div>
        </div>
        <div class="steps" style="gap: 8px;">
          <div class="step" style="padding: 16px 20px;">
            <div class="step-num" style="font-size: 1.6rem;">①</div>
            <div class="step-content"><h3>Nainstalovat aplikaci</h3><p>Stáhni MeshCore z obchodu nebo webu projektu.</p></div>
          </div>
          <div class="step" style="padding: 16px 20px;">
            <div class="step-num" style="font-size: 1.6rem;">②</div>
            <div class="step-content"><h3>Spárovat přes BLE</h3><p>Zapni Bluetooth, v aplikaci klikni na "Přidat zařízení" a vyber svůj uzel.</p></div>
          </div>
          <div class="step" style="padding: 16px 20px;">
            <div class="step-num" style="font-size: 1.6rem;">③</div>
            <div class="step-content"><h3>Importovat kanál komunity</h3><p>Naskenuj QR kód kanálu nebo zadej URL. Vidíš ostatní, oni vidí tebe.</p></div>
          </div>
          <div class="step" style="padding: 16px 20px;">
            <div class="step-num" style="font-size: 1.6rem;">④</div>
            <div class="step-content"><h3>Psát a sdílet polohu</h3><p>Posílej zprávy, sdílej GPS polohu, sleduj uzly na mapě.</p></div>
          </div>
        </div>
      </div>
      <div class="col-visual" style="display: flex; justify-content: center; align-items: flex-start; padding-top: 20px;">
        <div class="phone-mockup">
          <div class="phone-notch"><div class="phone-notch-cam"></div></div>
          <div class="phone-screen">
            <div class="phone-topbar">
              <span>9:41</span>
              <div class="phone-signal">
                <span></span><span></span><span></span><span></span>
              </div>
            </div>
            <div class="node-pill" style="align-self: center; margin: 4px 0;"><div class="node-pill-dot"></div> meshcore-cz · 4 uzly online</div>
            <div class="msg-system">Dnes, 14:32</div>
            <div class="msg-bubble msg-in">Ahoj! Jde slyšet z Letňan? 👋</div>
            <div class="msg-bubble msg-out">Jo, přes Žižkov repeater, 3 hopy</div>
            <div class="msg-bubble msg-in">Super signal! RSSI -95 dBm</div>
            <div class="msg-system">OK1XYZ sdílí polohu</div>
            <div class="msg-bubble msg-in" style="display:flex;align-items:center;gap:6px;">📍 Praha 3, 50.0833° N</div>
            <div class="msg-bubble msg-out">Vidím tě na mapě 🗺️</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="divider"></div>

<!-- COMPANION -->
<section id="companion">
  <div class="container">
    <div class="fade-up">
      <div class="section-label">Pokročilé použití</div>
      <h2 class="section-title">Companion klient</h2>
      <p class="section-desc">
        Companion je desktopový/webový klient pro MeshCore, který odemyká pokročilé funkce — správu uzlů, statistiky, automatizaci a propojení s jinými systémy.
      </p>
    </div>
    <div class="cards stagger">
      <div class="card">
        <div class="card-icon">🖥️</div>
        <h3>Instalace Companion</h3>
        <p>Companion běží jako Node.js aplikace nebo Docker kontejner. Připojí se k tvému uzlu přes USB/sériový port nebo TCP/IP. Webové rozhraní pak dostupné na <code style="color:var(--accent);font-family:'DM Mono',monospace;">localhost:8080</code>.</p>
        <div class="step-tags" style="margin-top: 16px;"><span class="tag">Node.js</span><span class="tag green">Docker</span></div>
      </div>
      <div class="card">
        <div class="card-icon">🔧</div>
        <h3>Co Companion umí</h3>
        <p>Zobrazení všech uzlů v síti, history zpráv, export statistik, správa kanálů, nastavení upozornění, propojení s Home Assistantem nebo vlastními skripty přes MQTT/REST API.</p>
        <div class="step-tags" style="margin-top: 16px;"><span class="tag purple">MQTT</span><span class="tag">REST API</span></div>
      </div>
      <div class="card">
        <div class="card-icon">🗺️</div>
        <h3>Mapa sítě v reálném čase</h3>
        <p>Companion zobrazuje interaktivní mapu všech uzlů, repeatérů a signálových tras. Vidíš, kudy putovaly tvoje zprávy, a kde je síť silná nebo slabá.</p>
        <div class="step-tags" style="margin-top: 16px;"><span class="tag green">Mapa</span><span class="tag">RSSI graf</span></div>
      </div>
    </div>

    <div class="code-block" style="margin-top: 40px;">
      <div class="code-header">
        <div class="code-dots"><span></span><span></span><span></span></div>
        <div class="code-title">Instalace — Docker (doporučeno)</div>
      </div>
      <pre><span class="cm"># Stáhnout a spustit Companion</span>
docker run -d \
  <span class="kw">--name</span> meshcore-companion \
  <span class="kw">--restart</span> unless-stopped \
  <span class="kw">-p</span> <span class="val">8080:8080</span> \
  <span class="kw">--device</span> /dev/ttyUSB0 \
  <span class="kw">-e</span> <span class="str">SERIAL_PORT=/dev/ttyUSB0</span> \
  <span class="kw">-e</span> <span class="str">BAUD_RATE=115200</span> \
  meshcore/companion:<span class="val">latest</span>

<span class="cm"># Otevřít v prohlížeči</span>
<span class="kw">open</span> http://localhost:<span class="val">8080</span></pre>
    </div>
  </div>
</section>

<div class="divider"></div>

<!-- FAQ -->
<section id="faq">
  <div class="container">
    <div class="fade-up">
      <div class="section-label">Časté otázky</div>
      <h2 class="section-title">FAQ</h2>
      <p class="section-desc">Nejčastější dotazy od nových uživatelů sítě.</p>
    </div>
    <div class="faq fade-up">
      <div class="faq-item">
        <div class="faq-q">Je MeshCore legální? Potřebuji licenci?<span class="faq-arrow">+</span></div>
        <div class="faq-a">V Česku funguje pásmo 868 MHz jako ISM pásmo (Industrial, Scientific, Medical) — pro provoz není potřeba žádná individuální licence. Je ale nutné dodržovat výkonové limity (25 mW / 14 dBm pro většinu kanálů) a duty cycle. Detailní text doplň dle aktuální legislativy ČTÚ.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Jak daleko dosáhne MeshCore signál?<span class="faq-arrow">+</span></div>
        <div class="faq-a">V otevřeném terénu (les, pole) počítej s 5–15 km přímou viditelností. Ve městě typicky 1–3 km mezi uzly. S dobře umístěným repeaterem na střeše nebo kopci pokrytí dramaticky roste. Zprávy navíc putují přes více hopů — takže efektivní dosah sítě může být i přes 50 km.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">MeshCore vs. Meshtastic — jaký je rozdíl?<span class="faq-arrow">+</span></div>
        <div class="faq-a">Obě sítě používají LoRa a mesh topologii, ale jsou to oddělené projekty s různým firmwarem a protokolem — vzájemně nekompatibilní. MeshCore se zaměřuje na jiné cíle a architekturu. Doplň vlastní porovnání dle aktuálních vlastností obou projektů.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Jsou zprávy šifrované?<span class="faq-arrow">+</span></div>
        <div class="faq-a">Ano, kanály v MeshCore používají symetrické šifrování (AES). Zprávy na veřejném kanálu jsou čitelné pro všechny členy kanálu. Přímé zprávy (DM) jsou šifrovány end-to-end. Doplň aktuální detaily z dokumentace projektu.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Kde najdu českou komunitu MeshCore?<span class="faq-arrow">+</span></div>
        <div class="faq-a">Česká komunita je aktivní na Telegramu, Discordu a radioamatérských fórech. Doplň přímé odkazy na skupiny, kde mohou nováčci hledat pomoc a sdílet zkušenosti.</div>
      </div>
    </div>
  </div>
</section>

<div class="divider"></div>

<!-- CTA -->
<section id="zapoj-se">
  <div class="container">
    <div class="highlight-box fade-up" style="text-align: center; padding: 80px 40px;">
      <div class="hero-badge" style="margin: 0 auto 24px; width: fit-content;"><span></span> Začni dnes — hardware stačí za 500 Kč</div>
      <h2 class="section-title" style="margin-bottom: 16px;">Jsi připraven<br>se připojit?</h2>
      <p style="color: var(--text-muted); max-width: 500px; margin: 0 auto 40px;">
        Celý proces trvá jedno odpoledne. Komunita ti ráda pomůže s prvními kroky.
        Doplňující text a výzva k akci patří sem.
      </p>
      <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
        <a href="#pripojeni" class="btn btn-primary" style="font-size: 1rem; padding: 16px 36px;">Začít → Krok 1</a>
        <a href="#" class="btn btn-ghost" style="font-size: 1rem; padding: 16px 36px;">Komunita na Telegramu</a>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="nav-logo" style="margin-bottom: 12px;">
          <div class="logo-dot"></div>
          MeshCore<span style="color:var(--accent)">.</span>cz
        </div>
        <p>Komunita kolem otevřené, decentralizované mesh sítě v České republice. Provozováno nadšenci pro nadšence.</p>
      </div>
      <div class="footer-col">
        <h5>Navigace</h5>
        <a href="#sit">Co je MeshCore</a>
        <a href="#pripojeni">Jak se připojit</a>
        <a href="#repeater">Repeater</a>
        <a href="#mobil">Mobilní aplikace</a>
        <a href="#companion">Companion klient</a>
        <a href="#faq">FAQ</a>
      </div>
      <div class="footer-col">
        <h5>Zdroje</h5>
        <a href="#">Oficiální dokumentace</a>
        <a href="#">GitHub repozitář</a>
        <a href="#">Komunita Discord</a>
        <a href="#">Telegram skupina</a>
        <a href="#">Mapa sítě</a>
        <a href="#">Firmware download</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2025 MeshCore.cz — komunitní projekt</span>
      <span style="color: var(--accent);">73 de OK · stay connected</span>
    </div>
  </div>
</footer>

<script>
// ─── MESH CANVAS ANIMATION ───
const canvas = document.getElementById('meshCanvas');
const ctx = canvas.getContext('2d');

function resize() {
  const parent = canvas.parentElement;
  canvas.width = parent.offsetWidth;
  canvas.height = parent.offsetHeight;
}
resize();
window.addEventListener('resize', resize);

const nodes = [];
const NODE_COUNT = 12;
const ACCENT = '#00c8a0';
const ACCENT2 = '#0090ff';

for (let i = 0; i < NODE_COUNT; i++) {
  nodes.push({
    x: Math.random() * 0.8 + 0.1,
    y: Math.random() * 0.8 + 0.1,
    vx: (Math.random() - 0.5) * 0.0008,
    vy: (Math.random() - 0.5) * 0.0008,
    r: Math.random() * 3 + 3,
    pulse: Math.random() * Math.PI * 2,
    active: Math.random() > 0.3,
    color: Math.random() > 0.6 ? ACCENT2 : ACCENT,
  });
}

let packet = null;
let packetTimer = 0;

function spawnPacket() {
  const from = Math.floor(Math.random() * nodes.length);
  let to;
  do { to = Math.floor(Math.random() * nodes.length); } while (to === from);
  packet = { from, to, t: 0 };
}

function draw(ts) {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  const W = canvas.width, H = canvas.height;

  // Update nodes
  nodes.forEach(n => {
    n.x += n.vx;
    n.y += n.vy;
    if (n.x < 0.05 || n.x > 0.95) n.vx *= -1;
    if (n.y < 0.05 || n.y > 0.95) n.vy *= -1;
    n.pulse += 0.04;
  });

  // Draw connections
  for (let i = 0; i < nodes.length; i++) {
    for (let j = i + 1; j < nodes.length; j++) {
      const a = nodes[i], b = nodes[j];
      const dx = (a.x - b.x) * W, dy = (a.y - b.y) * H;
      const dist = Math.sqrt(dx * dx + dy * dy);
      if (dist < 180) {
        ctx.beginPath();
        ctx.moveTo(a.x * W, a.y * H);
        ctx.lineTo(b.x * W, b.y * H);
        ctx.strokeStyle = `rgba(0,200,160,${(1 - dist / 180) * 0.18})`;
        ctx.lineWidth = 1;
        ctx.stroke();
      }
    }
  }

  // Packet
  packetTimer++;
  if (packetTimer > 80 || !packet) {
    if (packetTimer > 80) { packet = null; packetTimer = 0; spawnPacket(); }
  }
  if (packet) {
    packet.t += 0.025;
    if (packet.t > 1) { packet = null; } else {
      const a = nodes[packet.from], b = nodes[packet.to];
      const px = (a.x + (b.x - a.x) * packet.t) * W;
      const py = (a.y + (b.y - a.y) * packet.t) * H;
      ctx.beginPath();
      ctx.arc(px, py, 5, 0, Math.PI * 2);
      ctx.fillStyle = '#fff';
      ctx.shadowColor = ACCENT;
      ctx.shadowBlur = 15;
      ctx.fill();
      ctx.shadowBlur = 0;
    }
  }

  // Draw nodes
  nodes.forEach(n => {
    const x = n.x * W, y = n.y * H;
    const p = (Math.sin(n.pulse) + 1) / 2;

    // Glow ring
    ctx.beginPath();
    ctx.arc(x, y, n.r + 8 + p * 6, 0, Math.PI * 2);
    ctx.strokeStyle = n.color.replace(')', `,${0.08 + p * 0.1})`).replace('rgb', 'rgba').replace('#00c8a0', 'rgba(0,200,160,').replace('#0090ff', 'rgba(0,144,255,');
    // simpler:
    ctx.strokeStyle = n.active
      ? `rgba(0,200,160,${0.06 + p * 0.1})`
      : `rgba(0,144,255,${0.06 + p * 0.1})`;
    ctx.lineWidth = 2;
    ctx.stroke();

    // Core dot
    ctx.beginPath();
    ctx.arc(x, y, n.r, 0, Math.PI * 2);
    ctx.fillStyle = n.active ? ACCENT : ACCENT2;
    ctx.shadowColor = n.active ? ACCENT : ACCENT2;
    ctx.shadowBlur = 12;
    ctx.fill();
    ctx.shadowBlur = 0;
  });

  requestAnimationFrame(draw);
}
requestAnimationFrame(draw);

// ─── NAV TOGGLE ───
const toggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');
toggle.addEventListener('click', () => {
  navLinks.classList.toggle('open');
});
navLinks.querySelectorAll('a').forEach(a => {
  a.addEventListener('click', () => navLinks.classList.remove('open'));
});

// ─── SCROLL ANIMATIONS ───
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-up, .stagger').forEach(el => observer.observe(el));

// ─── FAQ ACCORDION ───
document.querySelectorAll('.faq-q').forEach(q => {
  q.addEventListener('click', () => {
    const item = q.parentElement;
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
  });
});
</script>
</body>
</html>
