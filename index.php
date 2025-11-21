<?php
// Simple index page that captures user's location in the browser
// and redirects to home.html with latitude/longitude parameters.
// This file intentionally uses client-side Geolocation API because
// server-side IP geolocation is less accurate and requires external APIs.
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Watchily — Redirecting...</title>
  <style>
    body{font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, Arial; background:#071029;color:#eaf6ff;display:flex;align-items:center;justify-content:center;height:100vh;margin:0}
    .card{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));padding:20px;border-radius:12px;max-width:520px;width:92%;text-align:center}
    .btn{margin-top:12px;padding:10px 14px;border-radius:10px;border:none;background:#7c5cff;color:#041028;font-weight:700;cursor:pointer}
    input{width:100%;padding:10px;border-radius:8px;border:none;margin-top:8px}
    .muted{color:rgba(234,240,255,0.75);font-size:14px}
  </style>
</head>
<body>
  <div class="card">
    <h2 style="margin:0 0 8px 0">Welcome to Watchily</h2>
    <div class="muted">We'll optionally use your location to personalize content. Your browser will ask for permission to share location.</div>

    <div id="status" style="margin-top:12px">Requesting location…</div>

    <div id="manual" style="margin-top:12px;display:none">
      <div class="muted">If you prefer, enter coordinates manually or click Continue without location.</div>
      <input id="lat" placeholder="Latitude (e.g. 19.0760)" />
      <input id="lon" placeholder="Longitude (e.g. 72.8777)" />
      <div style="display:flex;gap:8px;margin-top:8px">
        <button class="btn" id="useManual">Use Coordinates</button>
        <button class="btn" id="continueNoLoc">Continue Without Location</button>
      </div>
    </div>
  </div>

  <script>
    const statusEl = document.getElementById('status');
    const manualEl = document.getElementById('manual');

    function redirectToHome(params){
      const base = 'login.html';
      const search = new URLSearchParams(params).toString();
      const url = search ? base + '?' + search : base;
      window.location.replace(url);
    }

    function gotPosition(pos){
      const lat = pos.coords.latitude.toFixed(6);
      const lon = pos.coords.longitude.toFixed(6);
      statusEl.textContent = `Location found: ${lat}, ${lon}. Redirecting...`;
      redirectToHome({lat, lon});
    }

    function noPosition(msg){
      statusEl.textContent = msg || 'Unable to get location.';
      manualEl.style.display = 'block';
    }

    if(navigator.geolocation){
      navigator.geolocation.getCurrentPosition(gotPosition, function(err){
        console.warn('Geolocation error', err);
        noPosition('Location permission denied or unavailable.');
      }, {timeout:10000});
    } else {
      noPosition('Geolocation is not supported by your browser.');
    }

    document.getElementById('useManual').addEventListener('click', ()=>{
      const lat = document.getElementById('lat').value.trim();
      const lon = document.getElementById('lon').value.trim();
      if(lat && lon){ redirectToHome({lat, lon}); }
      else alert('Please enter both latitude and longitude.');
    });
    document.getElementById('continueNoLoc').addEventListener('click', ()=>{ redirectToHome({}); });
  </script>
</body>
</html>
