<!doctype html>
<html lang="cs">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>OK1SIM</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
  <style>
    :root{--bg1:#0f172a;--bg2:#0ea5a9;--text:#ffffff}
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family:'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
      background: linear-gradient(135deg, #0f172a 0%, #0b1220 40%, #0ea5a9 100%);
      color:var(--text);
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .container{padding:2rem;text-align:center}
    h1{
      font-size:clamp(3rem, 9vw, 8rem);
      margin:0;
      font-weight:900;
      letter-spacing:0.12em;
      text-transform:uppercase;
      text-shadow:0 8px 30px rgba(0,0,0,0.6);
    }
    @media (prefers-color-scheme: light){
      body{background:linear-gradient(135deg,#f8fafc 0%,#e6f7f7 100%);color:#0b1220}
      h1{text-shadow:0 6px 20px rgba(255,255,255,0.6)}
    }
  </style>
</head>
<body>
  <main class="container" role="main">
    <h1>OK1SIM</h1>
  </main>
</body>
</html>