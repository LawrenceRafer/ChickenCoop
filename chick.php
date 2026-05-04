<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CoopOS — Smart Chicken Coop System</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #0e1117;
  --bg2: #161b27;
  --bg3: #1e2535;
  --card: #1a2030;
  --border: #2a3347;
  --border2: #3a4560;
  --text: #e8eaf0;
  --text2: #8a95b0;
  --text3: #556070;
  --accent: #4ade80;
  --accent2: #22c55e;
  --danger: #f87171;
  --warn: #fbbf24;
  --info: #60a5fa;
  --purple: #a78bfa;
  --mono: 'IBM Plex Mono', monospace;
  --sans: 'IBM Plex Sans', sans-serif;
  --radius: 8px;
  --radius2: 12px;
}

* { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; font-family: var(--sans); background: var(--bg); color: var(--text); font-size: 14px; line-height: 1.6; }

/* ── AUTH SCREEN ── */
#authScreen {
  display: flex; align-items: center; justify-content: center;
  min-height: 100vh; background: var(--bg);
  background-image: radial-gradient(circle at 20% 50%, #1a2a1a 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, #0d1a2a 0%, transparent 50%);
}
.auth-box {
  width: 420px; background: var(--card); border: 1px solid var(--border);
  border-radius: var(--radius2); padding: 40px; position: relative; overflow: hidden;
}
.auth-box::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, var(--accent), var(--info), var(--purple));
}
.auth-logo { font-family: var(--mono); font-size: 22px; color: var(--accent); margin-bottom: 4px; }
.auth-sub { font-size: 12px; color: var(--text3); font-family: var(--mono); margin-bottom: 32px; }
.auth-tabs { display: flex; border-bottom: 1px solid var(--border); margin-bottom: 28px; }
.auth-tab {
  padding: 8px 20px; cursor: pointer; color: var(--text2); font-size: 13px;
  border-bottom: 2px solid transparent; margin-bottom: -1px; transition: all 0.2s;
}
.auth-tab.active { color: var(--accent); border-bottom-color: var(--accent); }
.field-group { margin-bottom: 16px; }
.field-group label { display: block; font-size: 11px; color: var(--text2); letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 6px; font-family: var(--mono); }
.field-group input, .field-group select {
  width: 100%; padding: 10px 14px; background: var(--bg3); border: 1px solid var(--border);
  border-radius: var(--radius); color: var(--text); font-size: 13px; font-family: var(--sans);
  transition: border-color 0.2s; outline: none;
}
.field-group input:focus, .field-group select:focus { border-color: var(--accent); }
.btn-primary {
  width: 100%; padding: 12px; background: var(--accent2); color: #000; border: none;
  border-radius: var(--radius); font-size: 14px; font-weight: 600; cursor: pointer;
  font-family: var(--sans); transition: background 0.2s; margin-top: 8px;
}
.btn-primary:hover { background: var(--accent); }
.auth-msg { padding: 10px 14px; border-radius: var(--radius); font-size: 12px; margin-top: 12px; display: none; }
.auth-msg.error { background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.3); color: var(--danger); }
.auth-msg.success { background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.3); color: var(--accent); }

/* ── APP SHELL ── */
#appShell { display: none; height: 100vh; flex-direction: column; }
#appShell.visible { display: flex; }

.topbar {
  height: 52px; background: var(--card); border-bottom: 1px solid var(--border);
  display: flex; align-items: center; padding: 0 20px; gap: 16px; flex-shrink: 0; position: relative;
}
.topbar-logo { font-family: var(--mono); font-size: 16px; color: var(--accent); flex-shrink: 0; }
.topbar-nav { display: flex; gap: 2px; flex: 1; }
.nav-btn {
  padding: 6px 14px; border: none; background: transparent; color: var(--text2);
  cursor: pointer; border-radius: var(--radius); font-size: 12px; font-family: var(--mono);
  transition: all 0.15s; white-space: nowrap;
}
.nav-btn:hover { background: var(--bg3); color: var(--text); }
.nav-btn.active { background: rgba(74,222,128,0.12); color: var(--accent); }
.topbar-user { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.user-chip {
  padding: 4px 12px; background: var(--bg3); border: 1px solid var(--border);
  border-radius: 20px; font-size: 11px; font-family: var(--mono); color: var(--text2);
}
.user-role-badge {
  padding: 2px 8px; border-radius: 4px; font-size: 10px; font-family: var(--mono);
  font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;
}
.role-admin { background: rgba(167,139,250,0.15); color: var(--purple); }
.role-operator { background: rgba(96,165,250,0.15); color: var(--info); }
.role-viewer { background: rgba(248,113,113,0.15); color: var(--danger); }
.btn-logout { padding: 5px 12px; background: transparent; border: 1px solid var(--border); border-radius: var(--radius); color: var(--text2); cursor: pointer; font-size: 11px; font-family: var(--mono); transition: all 0.2s; }
.btn-logout:hover { border-color: var(--danger); color: var(--danger); }

/* ── CONTENT ── */
.content-area { flex: 1; overflow-y: auto; padding: 24px; }
.page { display: none; }
.page.active { display: block; }

/* ── GRID ── */
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.grid-4s { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }

/* ── CARDS ── */
.card {
  background: var(--card); border: 1px solid var(--border);
  border-radius: var(--radius2); padding: 20px;
}
.card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.card-title { font-size: 13px; font-family: var(--mono); color: var(--text2); text-transform: uppercase; letter-spacing: 0.08em; }
.card-icon { font-size: 18px; }

/* ── STAT CARDS ── */
.stat-card { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius2); padding: 20px; }
.stat-label { font-size: 11px; color: var(--text3); font-family: var(--mono); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; }
.stat-value { font-size: 28px; font-weight: 600; font-family: var(--mono); line-height: 1; }
.stat-value.green { color: var(--accent); }
.stat-value.red { color: var(--danger); }
.stat-value.blue { color: var(--info); }
.stat-value.yellow { color: var(--warn); }
.stat-delta { font-size: 11px; color: var(--text3); margin-top: 6px; font-family: var(--mono); }

/* ── SENSOR CARDS ── */
.sensor-card {
  background: var(--card); border: 1px solid var(--border); border-radius: var(--radius2);
  padding: 18px; position: relative; overflow: hidden; transition: border-color 0.3s;
}
.sensor-card.alert { border-color: var(--danger); }
.sensor-card.warn { border-color: var(--warn); }
.sensor-card::after {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: var(--accent); transition: background 0.3s;
}
.sensor-card.alert::after { background: var(--danger); }
.sensor-card.warn::after { background: var(--warn); }
.sensor-name { font-family: var(--mono); font-size: 11px; color: var(--text3); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 12px; }
.sensor-vals { display: flex; gap: 20px; margin-bottom: 14px; }
.sensor-val { }
.sensor-val-num { font-size: 22px; font-weight: 600; font-family: var(--mono); color: var(--text); }
.sensor-val-unit { font-size: 11px; color: var(--text3); margin-left: 2px; }
.sensor-val-label { font-size: 10px; color: var(--text3); font-family: var(--mono); margin-top: 1px; }
.sensor-controls { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
.ctrl-group { display: flex; gap: 4px; align-items: center; }
.ctrl-label { font-size: 10px; color: var(--text3); font-family: var(--mono); margin-right: 4px; }
.btn-sm {
  padding: 4px 10px; border: 1px solid var(--border); background: var(--bg3);
  color: var(--text2); cursor: pointer; border-radius: 4px; font-size: 11px;
  font-family: var(--mono); transition: all 0.15s;
}
.btn-sm:hover { border-color: var(--border2); color: var(--text); }
.btn-sm.active-on { background: rgba(74,222,128,0.12); border-color: var(--accent2); color: var(--accent); }
.btn-sm.active-off { background: rgba(248,113,113,0.1); border-color: var(--danger); color: var(--danger); }
.btn-sm.active-dir { background: rgba(96,165,250,0.1); border-color: var(--info); color: var(--info); }
.status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 5px; }
.dot-on { background: var(--accent); box-shadow: 0 0 6px var(--accent); }
.dot-off { background: var(--danger); }
.dot-warn { background: var(--warn); }

/* ── TABLES ── */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
th { padding: 10px 14px; text-align: left; font-size: 10px; font-family: var(--mono); color: var(--text3); text-transform: uppercase; letter-spacing: 0.08em; border-bottom: 1px solid var(--border); font-weight: 400; white-space: nowrap; }
td { padding: 11px 14px; border-bottom: 1px solid rgba(42,51,71,0.5); vertical-align: middle; }
tr:last-child td { border-bottom: none; }
tr:hover td { background: rgba(255,255,255,0.02); }
.badge { padding: 2px 8px; border-radius: 4px; font-size: 10px; font-family: var(--mono); font-weight: 500; text-transform: uppercase; letter-spacing: 0.04em; }
.badge-on { background: rgba(74,222,128,0.12); color: var(--accent); }
.badge-off { background: rgba(248,113,113,0.1); color: var(--danger); }
.badge-in { background: rgba(96,165,250,0.1); color: var(--info); }
.badge-out { background: rgba(251,191,36,0.1); color: var(--warn); }
.badge-pending { background: rgba(251,191,36,0.1); color: var(--warn); }
.badge-approved { background: rgba(74,222,128,0.12); color: var(--accent); }
.badge-rejected { background: rgba(248,113,113,0.1); color: var(--danger); }
.badge-info { background: rgba(96,165,250,0.1); color: var(--info); }
.badge-admin { background: rgba(167,139,250,0.15); color: var(--purple); }
.badge-operator { background: rgba(96,165,250,0.1); color: var(--info); }
.badge-viewer { background: rgba(248,113,113,0.1); color: var(--danger); }

/* ── INPUTS ── */
input, select, textarea {
  background: var(--bg3); border: 1px solid var(--border); color: var(--text);
  border-radius: var(--radius); padding: 8px 12px; font-size: 13px;
  font-family: var(--sans); outline: none; transition: border-color 0.2s;
}
input:focus, select:focus, textarea:focus { border-color: var(--accent); }
select option { background: var(--bg3); }
.btn {
  padding: 8px 16px; border: 1px solid var(--border); background: var(--bg3);
  color: var(--text); cursor: pointer; border-radius: var(--radius); font-size: 13px;
  font-family: var(--sans); transition: all 0.2s;
}
.btn:hover { background: var(--bg2); border-color: var(--border2); }
.btn.btn-accent { background: var(--accent2); border-color: var(--accent2); color: #000; font-weight: 600; }
.btn.btn-accent:hover { background: var(--accent); border-color: var(--accent); }
.btn.btn-danger { background: rgba(248,113,113,0.1); border-color: var(--danger); color: var(--danger); }
.btn.btn-danger:hover { background: rgba(248,113,113,0.2); }
.btn.btn-info { background: rgba(96,165,250,0.1); border-color: var(--info); color: var(--info); }
.btn.btn-info:hover { background: rgba(96,165,250,0.2); }
.form-row { display: flex; gap: 12px; align-items: flex-end; margin-bottom: 16px; flex-wrap: wrap; }
.form-field { display: flex; flex-direction: column; gap: 5px; min-width: 140px; }
.form-field label { font-size: 11px; color: var(--text3); font-family: var(--mono); text-transform: uppercase; letter-spacing: 0.06em; }
.form-field input, .form-field select { width: 100%; }

/* ── PAGE HEADERS ── */
.page-header { margin-bottom: 24px; }
.page-title { font-size: 20px; font-weight: 600; color: var(--text); margin-bottom: 4px; }
.page-sub { font-size: 13px; color: var(--text2); }

/* ── MODAL ── */
.modal-overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 1000;
  display: none; align-items: center; justify-content: center;
}
.modal-overlay.open { display: flex; }
.modal {
  background: var(--card); border: 1px solid var(--border2); border-radius: var(--radius2);
  padding: 28px; width: 480px; max-width: 95vw; max-height: 90vh; overflow-y: auto;
}
.modal-title { font-size: 16px; font-weight: 600; margin-bottom: 20px; }
.modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; }

/* ── LOG ROWS ── */
.log-row { display: flex; align-items: flex-start; gap: 12px; padding: 10px 0; border-bottom: 1px solid rgba(42,51,71,0.4); }
.log-row:last-child { border-bottom: none; }
.log-time { font-size: 11px; color: var(--text3); font-family: var(--mono); white-space: nowrap; flex-shrink: 0; padding-top: 1px; }
.log-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
.log-msg { font-size: 12px; color: var(--text2); }
.log-user { font-weight: 500; color: var(--text); }

/* ── CHART ── */
.chart-wrap { position: relative; height: 220px; margin-top: 8px; }

/* ── DIVIDER ── */
.divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }

/* ── ALERT BANNER ── */
.alert-banner {
  padding: 10px 16px; border-radius: var(--radius); font-size: 12px; font-family: var(--mono);
  display: flex; align-items: center; gap: 10px; margin-bottom: 12px;
}
.alert-banner.danger { background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.2); color: var(--danger); }
.alert-banner.warn { background: rgba(251,191,36,0.1); border: 1px solid rgba(251,191,36,0.2); color: var(--warn); }

/* ── MISC ── */
.mono { font-family: var(--mono); }
.text-right { text-align: right; }
.flex { display: flex; }
.flex-col { display: flex; flex-direction: column; }
.items-center { align-items: center; }
.justify-between { justify-content: space-between; }
.gap-8 { gap: 8px; }
.gap-12 { gap: 12px; }
.mb-16 { margin-bottom: 16px; }
.mb-8 { margin-bottom: 8px; }
.mt-16 { margin-top: 16px; }
.mt-8 { margin-top: 8px; }
.text-sm { font-size: 12px; }
.text-xs { font-size: 11px; }
.color-dim { color: var(--text2); }
.color-dimmer { color: var(--text3); }
.color-green { color: var(--accent); }
.color-red { color: var(--danger); }
.color-blue { color: var(--info); }
.color-yellow { color: var(--warn); }
.color-purple { color: var(--purple); }
.access-denied { display: none; text-align: center; padding: 48px; color: var(--text3); font-family: var(--mono); font-size: 13px; }
.section-title { font-size: 13px; font-family: var(--mono); color: var(--text2); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.08em; }

/* Scrollbar */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: var(--text3); }

canvas { max-height: 100%; }
</style>
</head>
<body>

<!-- ══════════════════ AUTH SCREEN ══════════════════ -->
<div id="authScreen">
  <div class="auth-box">
    <div class="auth-logo">🐔 CoopOS</div>
    <div class="auth-sub">// smart_chicken_coop v2.0</div>
    <div class="auth-tabs">
      <div class="auth-tab active" onclick="switchAuthTab('login')">Login</div>
      <div class="auth-tab" onclick="switchAuthTab('register')">Register</div>
    </div>
    <!-- LOGIN -->
    <div id="loginForm">
      <div class="field-group">
        <label>Username</label>
        <input type="text" id="loginUser" placeholder="admin" value="admin">
      </div>
      <div class="field-group">
        <label>Password</label>
        <input type="password" id="loginPass" placeholder="••••••" value="admin123">
      </div>
      <button class="btn-primary" onclick="doLogin()">Sign In →</button>
      <div class="auth-msg" id="loginMsg"></div>
      <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);font-size:11px;color:var(--text3);font-family:var(--mono)">
        Demo: admin/admin123 · operator/op123 · viewer/view123
      </div>
    </div>
    <!-- REGISTER -->
    <div id="registerForm" style="display:none">
      <div class="field-group">
        <label>Full Name</label>
        <input type="text" id="regName" placeholder="Juan dela Cruz">
      </div>
      <div class="field-group">
        <label>Username</label>
        <input type="text" id="regUser" placeholder="juandc">
      </div>
      <div class="field-group">
        <label>Password</label>
        <input type="password" id="regPass" placeholder="Min 6 characters">
      </div>
      <div class="field-group">
        <label>Role</label>
        <select id="regRole">
          <option value="viewer">Viewer</option>
          <option value="operator">Operator</option>
        </select>
      </div>
      <button class="btn-primary" onclick="doRegister()">Create Account →</button>
      <div class="auth-msg" id="registerMsg"></div>
    </div>
  </div>
</div>

<!-- ══════════════════ APP SHELL ══════════════════ -->
<div id="appShell">
  <div class="topbar">
    <div class="topbar-logo">🐔 CoopOS</div>
    <div class="topbar-nav">
      <button class="nav-btn active" onclick="showPage('dashboard')">Dashboard</button>
      <button class="nav-btn" onclick="showPage('transactions')">Transactions</button>
      <button class="nav-btn" onclick="showPage('reports')">Reports</button>
      <button class="nav-btn" onclick="showPage('users', ['admin'])">Users</button>
    </div>
    <div class="topbar-user">
      <span class="user-chip" id="topbarUsername">—</span>
      <span class="user-role-badge" id="topbarRole">—</span>
      <button class="btn-logout" onclick="doLogout()">Logout</button>
    </div>
  </div>
  <div class="content-area">

    <!-- ── DASHBOARD PAGE ── -->
    <div class="page active" id="page-dashboard">
      <div class="page-header">
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div>
            <div class="page-title">IoT Sensor Dashboard</div>
            <div class="page-sub">Real-time monitoring · 4 zones · Auto-refresh 5s</div>
          </div>
          <div style="display:flex;align-items:center;gap:10px">
            <div style="font-size:11px;color:var(--text3);font-family:var(--mono)">Last updated: <span id="lastUpdateTime">—</span></div>
            <button class="btn" onclick="refreshSensors()">↻ Refresh</button>
          </div>
        </div>
      </div>

      <!-- Alert banners -->
      <div id="alertBanners"></div>

      <!-- Stat overview -->
      <div class="grid-4 mb-16">
        <div class="stat-card"><div class="stat-label">Avg Temperature</div><div class="stat-value green" id="avgTemp">—</div><div class="stat-delta">°C across all zones</div></div>
        <div class="stat-card"><div class="stat-label">Avg Humidity</div><div class="stat-value blue" id="avgHum">—</div><div class="stat-delta">% across all zones</div></div>
        <div class="stat-card"><div class="stat-label">Fans Active</div><div class="stat-value" id="fansActive">—</div><div class="stat-delta">of 4 total fans</div></div>
        <div class="stat-card"><div class="stat-label">System Status</div><div class="stat-value green" style="font-size:14px;padding-top:6px"><span class="status-dot dot-on"></span>ONLINE</div><div class="stat-delta">All sensors responding</div></div>
      </div>

      <!-- Sensor cards -->
      <div class="grid-4s mb-16" id="sensorCards"></div>

      <!-- Chart + log -->
      <div class="grid-2">
        <div class="card">
          <div class="card-header">
            <div class="card-title">Environmental History</div>
            <select id="chartMetric" onchange="updateChart()" style="font-size:11px;padding:4px 8px;font-family:var(--mono)">
              <option value="temp">Temperature</option>
              <option value="hum">Humidity</option>
            </select>
          </div>
          <div class="chart-wrap"><canvas id="envChart"></canvas></div>
        </div>
        <div class="card">
          <div class="card-header"><div class="card-title">Event Log</div><div style="font-size:11px;color:var(--text3);font-family:var(--mono)" id="eventCount">0 events</div></div>
          <div id="eventLog" style="max-height:220px;overflow-y:auto"></div>
        </div>
      </div>
    </div>

    <!-- ── TRANSACTIONS PAGE ── -->
    <div class="page" id="page-transactions">
      <div class="page-header">
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div>
            <div class="page-title">Transaction Processing</div>
            <div class="page-sub">Fan control commands, sensor calibrations & maintenance records</div>
          </div>
          <button class="btn btn-accent" onclick="openTxModal()">+ New Transaction</button>
        </div>
      </div>
      <div class="card mb-16">
        <div class="form-row">
          <div class="form-field" style="flex:1">
            <label>Search</label>
            <input type="text" id="txSearch" placeholder="Search transactions..." oninput="renderTxTable()">
          </div>
          <div class="form-field">
            <label>Type</label>
            <select id="txTypeFilter" onchange="renderTxTable()">
              <option value="">All Types</option>
              <option value="fan_control">Fan Control</option>
              <option value="calibration">Calibration</option>
              <option value="maintenance">Maintenance</option>
              <option value="alert_ack">Alert Ack</option>
            </select>
          </div>
          <div class="form-field">
            <label>Status</label>
            <select id="txStatusFilter" onchange="renderTxTable()">
              <option value="">All Status</option>
              <option value="approved">Approved</option>
              <option value="pending">Pending</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
          <div class="form-field">
            <label>Zone</label>
            <select id="txZoneFilter" onchange="renderTxTable()">
              <option value="">All Zones</option>
              <option value="North">North</option>
              <option value="West">West</option>
              <option value="South">South</option>
              <option value="East">East</option>
            </select>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="table-wrap">
          <table>
            <thead><tr><th>ID</th><th>Timestamp</th><th>Type</th><th>Zone</th><th>Description</th><th>User</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody id="txTableBody"></tbody>
          </table>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
          <div style="font-size:11px;color:var(--text3);font-family:var(--mono)" id="txCount">0 records</div>
          <div style="display:flex;gap:8px" id="txPagination"></div>
        </div>
      </div>
    </div>

    <!-- ── REPORTS PAGE ── -->
    <div class="page" id="page-reports">
      <div class="page-header">
        <div class="page-title">Dashboard & Reports</div>
        <div class="page-sub">Analytics, data export, and environmental summaries</div>
      </div>

      <div class="card mb-16">
        <div class="card-header"><div class="card-title">Report Filters</div></div>
        <div class="form-row">
          <div class="form-field">
            <label>Zone</label>
            <select id="repZone">
              <option value="all">All Zones</option>
              <option value="North">North</option>
              <option value="West">West</option>
              <option value="South">South</option>
              <option value="East">East</option>
            </select>
          </div>
          <div class="form-field">
            <label>Metric</label>
            <select id="repMetric">
              <option value="temperature">Temperature</option>
              <option value="humidity">Humidity</option>
            </select>
          </div>
          <div class="form-field">
            <label>Period</label>
            <select id="repPeriod">
              <option value="day">Last 24h</option>
              <option value="week">Last 7 days</option>
              <option value="month">Last 30 days</option>
            </select>
          </div>
          <div style="display:flex;gap:8px;align-items:flex-end">
            <button class="btn btn-accent" onclick="generateReport()">Generate</button>
            <button class="btn btn-info" onclick="exportCSV()">Export CSV</button>
            <button class="btn" onclick="exportJSON()">Export JSON</button>
          </div>
        </div>
      </div>

      <div class="grid-4 mb-16">
        <div class="stat-card"><div class="stat-label">Min Temp</div><div class="stat-value blue" id="repMinTemp">—</div><div class="stat-delta">°C recorded</div></div>
        <div class="stat-card"><div class="stat-label">Max Temp</div><div class="stat-value red" id="repMaxTemp">—</div><div class="stat-delta">°C recorded</div></div>
        <div class="stat-card"><div class="stat-label">Avg Humidity</div><div class="stat-value green" id="repAvgHum">—</div><div class="stat-delta">% average</div></div>
        <div class="stat-card"><div class="stat-label">Total Logs</div><div class="stat-value yellow" id="repCount">—</div><div class="stat-delta">data points</div></div>
      </div>

      <div class="grid-2 mb-16">
        <div class="card">
          <div class="card-header"><div class="card-title">Trend Chart</div></div>
          <div class="chart-wrap"><canvas id="reportChart"></canvas></div>
        </div>
        <div class="card">
          <div class="card-header"><div class="card-title">Zone Comparison</div></div>
          <div class="chart-wrap"><canvas id="zoneChart"></canvas></div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><div class="card-title">Sensor Log History</div></div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Timestamp</th><th>Zone</th><th>Temperature (°C)</th><th>Humidity (%)</th><th>Fan</th><th>Direction</th></tr></thead>
            <tbody id="repTableBody"></tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ── USERS PAGE ── -->
    <div class="page" id="page-users">
      <div id="usersPageContent">
        <div class="page-header">
          <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
              <div class="page-title">User Management</div>
              <div class="page-sub">Roles, permissions, account control & audit log</div>
            </div>
            <button class="btn btn-accent" onclick="openAddUserModal()">+ Add User</button>
          </div>
        </div>

        <div class="grid-3 mb-16">
          <div class="stat-card"><div class="stat-label">Total Users</div><div class="stat-value" id="totalUsers">—</div><div class="stat-delta">registered accounts</div></div>
          <div class="stat-card"><div class="stat-label">Active Sessions</div><div class="stat-value green">1</div><div class="stat-delta">currently online</div></div>
          <div class="stat-card"><div class="stat-label">Admins</div><div class="stat-value yellow" id="totalAdmins">—</div><div class="stat-delta">with full access</div></div>
        </div>

        <div class="grid-2 mb-16">
          <div class="card">
            <div class="card-header"><div class="card-title">User Accounts</div></div>
            <div class="table-wrap">
              <table>
                <thead><tr><th>Name</th><th>Username</th><th>Role</th><th>Created</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody id="usersTableBody"></tbody>
              </table>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><div class="card-title">Permissions Matrix</div></div>
            <div class="table-wrap">
              <table>
                <thead><tr><th>Permission</th><th>Admin</th><th>Operator</th><th>Viewer</th></tr></thead>
                <tbody>
                  <tr><td>View Dashboard</td><td>✅</td><td>✅</td><td>✅</td></tr>
                  <tr><td>Control Fans</td><td>✅</td><td>✅</td><td>❌</td></tr>
                  <tr><td>View Transactions</td><td>✅</td><td>✅</td><td>✅</td></tr>
                  <tr><td>Approve Transactions</td><td>✅</td><td>❌</td><td>❌</td></tr>
                  <tr><td>Export Reports</td><td>✅</td><td>✅</td><td>❌</td></tr>
                  <tr><td>Manage Users</td><td>✅</td><td>❌</td><td>❌</td></tr>
                  <tr><td>View Audit Log</td><td>✅</td><td>✅</td><td>❌</td></tr>
                  <tr><td>Delete Records</td><td>✅</td><td>❌</td><td>❌</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><div class="card-title">Audit Log</div><div style="font-size:11px;color:var(--text3);font-family:var(--mono)" id="auditCount">0 entries</div></div>
          <div id="auditLog" style="max-height:280px;overflow-y:auto"></div>
        </div>
      </div>
      <div class="access-denied" id="usersAccessDenied">
        <div style="font-size:32px;margin-bottom:12px">🔒</div>
        <div>ACCESS DENIED</div>
        <div style="margin-top:8px;font-size:12px;color:var(--text3)">Admin role required</div>
      </div>
    </div>

  </div><!-- end content-area -->
</div><!-- end appShell -->

<!-- ══════════════════ MODALS ══════════════════ -->

<!-- Transaction Modal -->
<div class="modal-overlay" id="txModal">
  <div class="modal">
    <div class="modal-title">New Transaction</div>
    <div class="form-field mb-16">
      <label>Transaction Type</label>
      <select id="txType" style="width:100%" onchange="updateTxForm()">
        <option value="fan_control">Fan Control</option>
        <option value="calibration">Calibration</option>
        <option value="maintenance">Maintenance</option>
        <option value="alert_ack">Alert Acknowledgement</option>
      </select>
    </div>
    <div class="form-field mb-16">
      <label>Zone</label>
      <select id="txZone" style="width:100%">
        <option>North</option><option>West</option><option>South</option><option>East</option>
        <option value="all">All Zones</option>
      </select>
    </div>
    <div class="form-field mb-16" id="txActionField">
      <label>Action</label>
      <select id="txAction" style="width:100%">
        <option value="FAN_ON">Turn Fan ON</option>
        <option value="FAN_OFF">Turn Fan OFF</option>
        <option value="DIR_IN">Set Direction IN</option>
        <option value="DIR_OUT">Set Direction OUT</option>
      </select>
    </div>
    <div class="form-field mb-16">
      <label>Notes</label>
      <textarea id="txNotes" rows="3" style="width:100%;resize:vertical" placeholder="Optional notes..."></textarea>
    </div>
    <div class="form-field mb-16">
      <label>Priority</label>
      <select id="txPriority" style="width:100%">
        <option value="normal">Normal</option>
        <option value="high">High</option>
        <option value="urgent">Urgent</option>
      </select>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeTxModal()">Cancel</button>
      <button class="btn btn-accent" onclick="submitTransaction()">Submit Transaction</button>
    </div>
  </div>
</div>

<!-- Add User Modal -->
<div class="modal-overlay" id="addUserModal">
  <div class="modal">
    <div class="modal-title">Add New User</div>
    <div class="form-field mb-16">
      <label>Full Name</label>
      <input type="text" id="newUserName" style="width:100%" placeholder="Full Name">
    </div>
    <div class="form-field mb-16">
      <label>Username</label>
      <input type="text" id="newUsername" style="width:100%" placeholder="username">
    </div>
    <div class="form-field mb-16">
      <label>Password</label>
      <input type="password" id="newUserPass" style="width:100%" placeholder="Min 6 characters">
    </div>
    <div class="form-field mb-16">
      <label>Role</label>
      <select id="newUserRole" style="width:100%">
        <option value="viewer">Viewer</option>
        <option value="operator">Operator</option>
        <option value="admin">Admin</option>
      </select>
    </div>
    <div class="auth-msg" id="addUserMsg"></div>
    <div class="modal-footer">
      <button class="btn" onclick="closeAddUserModal()">Cancel</button>
      <button class="btn btn-accent" onclick="submitAddUser()">Create User</button>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal-overlay" id="editUserModal">
  <div class="modal">
    <div class="modal-title">Edit User</div>
    <input type="hidden" id="editUserTarget">
    <div class="form-field mb-16">
      <label>Full Name</label>
      <input type="text" id="editUserName" style="width:100%">
    </div>
    <div class="form-field mb-16">
      <label>Role</label>
      <select id="editUserRole" style="width:100%">
        <option value="viewer">Viewer</option>
        <option value="operator">Operator</option>
        <option value="admin">Admin</option>
      </select>
    </div>
    <div class="form-field mb-16">
      <label>Status</label>
      <select id="editUserStatus" style="width:100%">
        <option value="active">Active</option>
        <option value="suspended">Suspended</option>
      </select>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeEditUserModal()">Cancel</button>
      <button class="btn btn-accent" onclick="submitEditUser()">Save Changes</button>
    </div>
  </div>
</div>

<script>
// ═══════════════════════════════════════════
// DATA STORE
// ═══════════════════════════════════════════
const DB = {
  get(k) { try { return JSON.parse(localStorage.getItem('coop_'+k)) || null; } catch { return null; } },
  set(k,v) { localStorage.setItem('coop_'+k, JSON.stringify(v)); },
};

// Initialize default data
function initDB() {
  if (!DB.get('users')) {
    DB.set('users', [
      { id:'u1', name:'Admin User', username:'admin', password:'admin123', role:'admin', status:'active', createdAt: now() },
      { id:'u2', name:'Operator Joe', username:'operator', password:'op123', role:'operator', status:'active', createdAt: now() },
      { id:'u3', name:'Viewer Ana', username:'viewer', password:'view123', role:'viewer', status:'active', createdAt: now() },
    ]);
  }
  if (!DB.get('sensorLogs')) DB.set('sensorLogs', []);
  if (!DB.get('transactions')) {
    DB.set('transactions', [
      { id:'tx001', type:'fan_control', zone:'North', action:'FAN_ON', notes:'Morning routine', user:'admin', status:'approved', priority:'normal', ts: now(-3600000) },
      { id:'tx002', type:'calibration', zone:'West', action:'CALIBRATE', notes:'Monthly calibration', user:'operator', status:'approved', priority:'normal', ts: now(-7200000) },
      { id:'tx003', type:'maintenance', zone:'South', action:'INSPECT', notes:'Weekly inspection', user:'admin', status:'pending', priority:'high', ts: now(-1800000) },
      { id:'tx004', type:'fan_control', zone:'East', action:'FAN_OFF', notes:'', user:'operator', status:'approved', priority:'normal', ts: now(-900000) },
    ]);
  }
  if (!DB.get('auditLog')) DB.set('auditLog', []);
  if (!DB.get('sensorState')) {
    DB.set('sensorState', [
      { id:1, name:'North', fanStatus:'ON', direction:'IN' },
      { id:2, name:'West', fanStatus:'OFF', direction:'OUT' },
      { id:3, name:'South', fanStatus:'ON', direction:'IN' },
      { id:4, name:'East', fanStatus:'OFF', direction:'IN' },
    ]);
  }
}

function now(offset=0) { return new Date(Date.now()+offset).toISOString(); }
function uid() { return 'x'+Math.random().toString(36).slice(2,10); }
function fmtDate(iso) {
  const d = new Date(iso);
  return d.toLocaleDateString()+' '+d.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
}

// ═══════════════════════════════════════════
// AUTH MODULE
// ═══════════════════════════════════════════
let currentUser = null;
let sensorInterval = null;

function switchAuthTab(tab) {
  document.querySelectorAll('.auth-tab').forEach((t,i) => t.classList.toggle('active', i===(tab==='login'?0:1)));
  document.getElementById('loginForm').style.display = tab==='login' ? '' : 'none';
  document.getElementById('registerForm').style.display = tab==='register' ? '' : 'none';
}

function showAuthMsg(id, msg, type) {
  const el = document.getElementById(id);
  el.textContent = msg; el.className = 'auth-msg '+type; el.style.display = 'block';
}

function doLogin() {
  const u = document.getElementById('loginUser').value.trim();
  const p = document.getElementById('loginPass').value;
  const users = DB.get('users') || [];
  const user = users.find(x => x.username===u && x.password===p && x.status==='active');
  if (!user) { showAuthMsg('loginMsg','Invalid credentials or account suspended','error'); return; }
  currentUser = user;
  DB.set('session', { userId: user.id, loginTime: now() });
  addAudit(`${user.name} logged in`, 'login', user.username);
  launchApp();
}

function doRegister() {
  const name = document.getElementById('regName').value.trim();
  const username = document.getElementById('regUser').value.trim();
  const pass = document.getElementById('regPass').value;
  const role = document.getElementById('regRole').value;
  if (!name || !username || !pass) { showAuthMsg('registerMsg','All fields required','error'); return; }
  if (pass.length < 6) { showAuthMsg('registerMsg','Password min 6 characters','error'); return; }
  const users = DB.get('users') || [];
  if (users.find(x => x.username===username)) { showAuthMsg('registerMsg','Username already taken','error'); return; }
  const nu = { id:uid(), name, username, password:pass, role, status:'active', createdAt:now() };
  users.push(nu); DB.set('users', users);
  showAuthMsg('registerMsg','Account created! You can now login.','success');
  addAudit(`New user registered: ${username} (${role})`, 'register', 'system');
}

function doLogout() {
  addAudit(`${currentUser.name} logged out`, 'logout', currentUser.username);
  currentUser = null;
  DB.set('session', null);
  clearInterval(sensorInterval);
  envChart && envChart.destroy(); envChart=null;
  reportChart && reportChart.destroy(); reportChart=null;
  zoneChart && zoneChart.destroy(); zoneChart=null;
  document.getElementById('appShell').classList.remove('visible');
  document.getElementById('authScreen').style.display = 'flex';
}

function launchApp() {
  document.getElementById('authScreen').style.display = 'none';
  document.getElementById('appShell').classList.add('visible');
  document.getElementById('topbarUsername').textContent = currentUser.name;
  const rb = document.getElementById('topbarRole');
  rb.textContent = currentUser.role;
  rb.className = 'user-role-badge role-'+currentUser.role;
  showPage('dashboard');
  initSensors();
  sensorInterval = setInterval(refreshSensors, 5000);
  generateReport();
}

// ═══════════════════════════════════════════
// NAVIGATION
// ═══════════════════════════════════════════
function showPage(name, allowedRoles=[]) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
  const page = document.getElementById('page-'+name);
  if (page) page.classList.add('active');
  document.querySelectorAll('.nav-btn').forEach(b => {
    if (b.textContent.toLowerCase().includes(name) || (name==='dashboard' && b.textContent.includes('Dashboard'))) b.classList.add('active');
  });
  if (name==='transactions') renderTxTable();
  if (name==='users') loadUsersPage();
  if (name==='reports') generateReport();
}

// ═══════════════════════════════════════════
// IoT SENSOR MODULE
// ═══════════════════════════════════════════
let sensorData = [];
let envChart = null;
const ZONES = ['North','West','South','East'];
const tempHistory = { North:[], West:[], South:[], East:[] };
const humHistory = { North:[], West:[], South:[], East:[] };
const timeLabels = [];

function randRange(min, max) { return +(min + Math.random()*(max-min)).toFixed(1); }

function generateSensorReading(state) {
  return {
    temperature: randRange(26, 36),
    humidity: randRange(52, 78),
    fanStatus: state.fanStatus,
    direction: state.direction,
    zone: state.name,
    id: state.id,
  };
}

function initSensors() {
  const states = DB.get('sensorState') || [];
  const container = document.getElementById('sensorCards');
  container.innerHTML = '';
  ZONES.forEach((zone, i) => {
    const state = states.find(s=>s.name===zone) || {name:zone,fanStatus:'OFF',direction:'IN'};
    const card = document.createElement('div');
    card.className = 'sensor-card'; card.id = 'sc'+zone;
    card.innerHTML = `
      <div class="sensor-name"><span class="status-dot dot-on" id="dot${zone}"></span>${zone} Zone</div>
      <div class="sensor-vals">
        <div class="sensor-val">
          <div><span class="sensor-val-num" id="st${zone}">--</span><span class="sensor-val-unit">°C</span></div>
          <div class="sensor-val-label">TEMP</div>
        </div>
        <div class="sensor-val">
          <div><span class="sensor-val-num" id="sh${zone}">--</span><span class="sensor-val-unit">%</span></div>
          <div class="sensor-val-label">HUMIDITY</div>
        </div>
      </div>
      <div class="sensor-controls">
        <div class="ctrl-group">
          <span class="ctrl-label">FAN:</span>
          <button class="btn-sm ${state.fanStatus==='ON'?'active-on':''}" id="bon${zone}" onclick="setFan('${zone}','ON')">ON</button>
          <button class="btn-sm ${state.fanStatus==='OFF'?'active-off':''}" id="boff${zone}" onclick="setFan('${zone}','OFF')">OFF</button>
        </div>
        <div class="ctrl-group">
          <span class="ctrl-label">DIR:</span>
          <button class="btn-sm ${state.direction==='IN'?'active-dir':''}" id="bin${zone}" onclick="setDir('${zone}','IN')">IN</button>
          <button class="btn-sm ${state.direction==='OUT'?'active-dir':''}" id="bout${zone}" onclick="setDir('${zone}','OUT')">OUT</button>
        </div>
      </div>
    `;
    container.appendChild(card);
  });
  initEnvChart();
  refreshSensors();
}

function refreshSensors() {
  if (!currentUser) return;
  const states = DB.get('sensorState') || [];
  sensorData = states.map(s => generateSensorReading(s));
  updateSensorCards();
  updateSensorStats();
  updateEventLog();
  pushSensorLog();
  document.getElementById('lastUpdateTime').textContent = new Date().toLocaleTimeString();
}

function updateSensorCards() {
  let alerts = [];
  sensorData.forEach(s => {
    const tempEl = document.getElementById('st'+s.zone);
    const humEl = document.getElementById('sh'+s.zone);
    const card = document.getElementById('sc'+s.zone);
    if (tempEl) tempEl.textContent = s.temperature;
    if (humEl) humEl.textContent = s.humidity;

    const isHot = s.temperature > 33;
    const isHum = s.humidity > 72;
    if (card) {
      card.className = 'sensor-card' + (isHot ? ' alert' : isHum ? ' warn' : '');
    }
    if (isHot) alerts.push({zone:s.zone, msg:`High temperature: ${s.temperature}°C`, type:'danger'});
    if (isHum) alerts.push({zone:s.zone, msg:`High humidity: ${s.humidity}%`, type:'warn'});

    const now = new Date();
    if (timeLabels.length < 12) timeLabels.push(now.getHours()+':'+String(now.getMinutes()).padStart(2,'0'));
    else { timeLabels.shift(); timeLabels.push(now.getHours()+':'+String(now.getMinutes()).padStart(2,'0')); }

    const th = tempHistory[s.zone];
    const hh = humHistory[s.zone];
    th.push(s.temperature); if (th.length > 12) th.shift();
    hh.push(s.humidity); if (hh.length > 12) hh.shift();
  });

  const banner = document.getElementById('alertBanners');
  banner.innerHTML = alerts.map(a=>`<div class="alert-banner ${a.type}">⚠ <strong>${a.zone}:</strong> ${a.msg}</div>`).join('');
  updateEnvChart();
}

function updateSensorStats() {
  const temps = sensorData.map(s=>s.temperature);
  const hums = sensorData.map(s=>s.humidity);
  const fansOn = sensorData.filter(s=>s.fanStatus==='ON').length;
  document.getElementById('avgTemp').textContent = temps.length ? (temps.reduce((a,b)=>a+b,0)/temps.length).toFixed(1) : '—';
  document.getElementById('avgHum').textContent = hums.length ? (hums.reduce((a,b)=>a+b,0)/hums.length).toFixed(1) : '—';
  const fanEl = document.getElementById('fansActive');
  fanEl.textContent = fansOn;
  fanEl.className = 'stat-value '+(fansOn>0?'green':'');
}

function setFan(zone, status) {
  if (!canControl()) return;
  const states = DB.get('sensorState') || [];
  const s = states.find(x=>x.name===zone);
  if (s) { s.fanStatus = status; DB.set('sensorState', states); }
  ['ON','OFF'].forEach(v => {
    const btn = document.getElementById('b'+v.toLowerCase()+zone);
    if (btn) btn.className = 'btn-sm'+(v===status?' '+(v==='ON'?'active-on':'active-off'):'');
  });
  logEvent(`Fan ${status} → ${zone}`, 'fan');
  addAudit(`Set ${zone} fan ${status}`, 'fan_control', currentUser.username);
  autoTx('fan_control', zone, 'FAN_'+status);
  const sd = sensorData.find(x=>x.zone===zone);
  if (sd) sd.fanStatus = status;
  updateSensorStats();
}

function setDir(zone, dir) {
  if (!canControl()) return;
  const states = DB.get('sensorState') || [];
  const s = states.find(x=>x.name===zone);
  if (s) { s.direction = dir; DB.set('sensorState', states); }
  ['IN','OUT'].forEach(v => {
    const btn = document.getElementById('b'+v.toLowerCase()+zone);
    if (btn) btn.className = 'btn-sm'+(v===dir?' active-dir':'');
  });
  logEvent(`Direction ${dir} → ${zone}`, 'dir');
  addAudit(`Set ${zone} direction ${dir}`, 'fan_control', currentUser.username);
}

function canControl() {
  if (currentUser.role==='viewer') { alert('Viewer role cannot control devices.'); return false; }
  return true;
}

function autoTx(type, zone, action) {
  const txs = DB.get('transactions') || [];
  txs.unshift({ id:'tx'+uid(), type, zone, action, notes:'Auto from dashboard', user:currentUser.username, status:'approved', priority:'normal', ts:now() });
  DB.set('transactions', txs);
}

// Event Log
const eventQueue = [];
function logEvent(msg, type) {
  const colors = { fan:'var(--accent)', dir:'var(--info)', alert:'var(--danger)', sys:'var(--text3)' };
  eventQueue.unshift({ msg, type, time: new Date().toLocaleTimeString(), color: colors[type]||'var(--text3)' });
  if (eventQueue.length > 50) eventQueue.pop();
  updateEventLog();
}
function updateEventLog() {
  const el = document.getElementById('eventLog');
  if (!el) return;
  if (!eventQueue.length) { el.innerHTML = '<div style="text-align:center;padding:24px;color:var(--text3);font-size:12px;font-family:var(--mono)">No events yet</div>'; return; }
  el.innerHTML = eventQueue.slice(0,20).map(e=>`
    <div class="log-row">
      <div class="log-time">${e.time}</div>
      <div class="log-dot" style="background:${e.color}"></div>
      <div class="log-msg">${e.msg}</div>
    </div>`).join('');
  document.getElementById('eventCount').textContent = eventQueue.length+' events';
}

function pushSensorLog() {
  const logs = DB.get('sensorLogs') || [];
  sensorData.forEach(s => {
    logs.push({ ts:now(), zone:s.zone, temperature:s.temperature, humidity:s.humidity, fanStatus:s.fanStatus, direction:s.direction });
  });
  if (logs.length > 2000) logs.splice(0, logs.length-2000);
  DB.set('sensorLogs', logs);
}

// ENV CHART
const chartColors = { North:'#4ade80', West:'#60a5fa', South:'#fbbf24', East:'#f87171' };
function initEnvChart() {
  const ctx = document.getElementById('envChart');
  if (!ctx) return;
  envChart = new Chart(ctx, {
    type:'line',
    data:{ labels:[], datasets: ZONES.map(z=>({ label:z, data:[], borderColor:chartColors[z], backgroundColor:'transparent', tension:0.4, pointRadius:3, borderWidth:2 })) },
    options:{ responsive:true, maintainAspectRatio:false, animation:false,
      plugins:{ legend:{ labels:{ color:'#8a95b0', font:{size:11} } } },
      scales:{ x:{ticks:{color:'#556070',font:{size:10}},grid:{color:'#2a3347'}},y:{ticks:{color:'#556070',font:{size:10}},grid:{color:'#2a3347'},beginAtZero:false} }
    }
  });
}
function updateEnvChart() {
  if (!envChart) return;
  const metric = document.getElementById('chartMetric')?.value || 'temp';
  const hist = metric==='temp' ? tempHistory : humHistory;
  const llen = Math.max(...ZONES.map(z=>(hist[z]||[]).length));
  envChart.data.labels = timeLabels.slice(-llen);
  ZONES.forEach((z,i) => { envChart.data.datasets[i].data = (hist[z]||[]).slice(-12); });
  envChart.update('none');
}
function updateChart() { updateEnvChart(); }

// ═══════════════════════════════════════════
// TRANSACTIONS MODULE
// ═══════════════════════════════════════════
let txPage = 1;
const TX_PER_PAGE = 10;

function renderTxTable() {
  const txs = DB.get('transactions') || [];
  const search = document.getElementById('txSearch').value.toLowerCase();
  const typeF = document.getElementById('txTypeFilter').value;
  const statusF = document.getElementById('txStatusFilter').value;
  const zoneF = document.getElementById('txZoneFilter').value;

  const filtered = txs.filter(t => {
    if (search && !JSON.stringify(t).toLowerCase().includes(search)) return false;
    if (typeF && t.type!==typeF) return false;
    if (statusF && t.status!==statusF) return false;
    if (zoneF && t.zone!==zoneF) return false;
    return true;
  });

  const totalPages = Math.ceil(filtered.length/TX_PER_PAGE) || 1;
  if (txPage > totalPages) txPage = totalPages;
  const slice = filtered.slice((txPage-1)*TX_PER_PAGE, txPage*TX_PER_PAGE);

  const body = document.getElementById('txTableBody');
  if (!slice.length) { body.innerHTML = '<tr><td colspan="8" style="text-align:center;color:var(--text3);padding:24px">No transactions found</td></tr>'; }
  else {
    body.innerHTML = slice.map(t => {
      const canApprove = currentUser.role==='admin' && t.status==='pending';
      const canDelete = currentUser.role==='admin';
      return `<tr>
        <td class="mono" style="font-size:11px;color:var(--text3)">${t.id}</td>
        <td style="font-size:11px;color:var(--text3)">${fmtDate(t.ts)}</td>
        <td><span class="badge badge-info">${t.type.replace('_',' ')}</span></td>
        <td>${t.zone}</td>
        <td style="font-size:12px">${t.action||'—'} ${t.notes?'<span style="color:var(--text3)">· '+t.notes+'</span>':''}</td>
        <td class="mono" style="font-size:11px">${t.user}</td>
        <td><span class="badge badge-${t.status}">${t.status}</span></td>
        <td style="white-space:nowrap">
          ${canApprove?`<button class="btn-sm" onclick="approveTx('${t.id}')">✓ Approve</button> <button class="btn-sm" style="color:var(--danger);border-color:var(--danger)" onclick="rejectTx('${t.id}')">✗ Reject</button>`:''}
          ${canDelete?`<button class="btn-sm" style="margin-left:4px" onclick="deleteTx('${t.id}')">Del</button>`:''}
        </td>
      </tr>`;
    }).join('');
  }

  document.getElementById('txCount').textContent = filtered.length+' records';
  // Pagination
  const pag = document.getElementById('txPagination');
  pag.innerHTML = '';
  for (let i=1;i<=totalPages;i++) {
    const b = document.createElement('button');
    b.className = 'btn-sm'+(i===txPage?' active-dir':'');
    b.textContent = i;
    b.onclick = ()=>{ txPage=i; renderTxTable(); };
    pag.appendChild(b);
  }
}

function approveTx(id) {
  const txs = DB.get('transactions') || [];
  const t = txs.find(x=>x.id===id);
  if (t) { t.status='approved'; DB.set('transactions',txs); addAudit(`Approved transaction ${id}`,'approve',currentUser.username); renderTxTable(); }
}
function rejectTx(id) {
  const txs = DB.get('transactions') || [];
  const t = txs.find(x=>x.id===id);
  if (t) { t.status='rejected'; DB.set('transactions',txs); addAudit(`Rejected transaction ${id}`,'reject',currentUser.username); renderTxTable(); }
}
function deleteTx(id) {
  if (!confirm('Delete this transaction?')) return;
  const txs = (DB.get('transactions')||[]).filter(x=>x.id!==id);
  DB.set('transactions',txs); addAudit(`Deleted transaction ${id}`,'delete',currentUser.username); renderTxTable();
}

function openTxModal() { document.getElementById('txModal').classList.add('open'); }
function closeTxModal() { document.getElementById('txModal').classList.remove('open'); }

function submitTransaction() {
  const type = document.getElementById('txType').value;
  const zone = document.getElementById('txZone').value;
  const action = document.getElementById('txAction').value;
  const notes = document.getElementById('txNotes').value;
  const priority = document.getElementById('txPriority').value;
  const status = currentUser.role==='admin' ? 'approved' : 'pending';
  const txs = DB.get('transactions') || [];
  const newTx = { id:'tx'+uid(), type, zone, action, notes, user:currentUser.username, status, priority, ts:now() };
  txs.unshift(newTx);
  DB.set('transactions', txs);
  addAudit(`Created ${type} transaction for ${zone}`,'transaction',currentUser.username);
  logEvent(`New transaction: ${type} → ${zone}`,'sys');
  closeTxModal();
  renderTxTable();
  document.getElementById('txNotes').value = '';
}

// ═══════════════════════════════════════════
// REPORTS MODULE
// ═══════════════════════════════════════════
let reportChart = null, zoneChart = null;

function generateReport() {
  const logs = DB.get('sensorLogs') || [];
  const zone = document.getElementById('repZone')?.value || 'all';
  const metric = document.getElementById('repMetric')?.value || 'temperature';
  const period = document.getElementById('repPeriod')?.value || 'day';

  const cutoff = new Date(Date.now() - (period==='day'?86400000:period==='week'?604800000:2592000000));
  const filtered = logs.filter(l => new Date(l.ts) >= cutoff && (zone==='all' || l.zone===zone));

  if (!filtered.length) {
    // Fallback to simulated data for demo
    const sim = [];
    for (let i=24;i>=0;i--) {
      ZONES.forEach(z=>{
        sim.push({ ts:new Date(Date.now()-i*3600000).toISOString(), zone:z, temperature:randRange(26,36), humidity:randRange(55,75), fanStatus:'ON', direction:'IN' });
      });
    }
    return renderReportFromData(sim, zone, metric);
  }
  renderReportFromData(filtered, zone, metric);
}

function renderReportFromData(filtered, zone, metric) {
  const temps = filtered.map(l=>l.temperature).filter(Boolean);
  const hums = filtered.map(l=>l.humidity).filter(Boolean);

  document.getElementById('repMinTemp').textContent = temps.length ? Math.min(...temps).toFixed(1) : '—';
  document.getElementById('repMaxTemp').textContent = temps.length ? Math.max(...temps).toFixed(1) : '—';
  document.getElementById('repAvgHum').textContent = hums.length ? (hums.reduce((a,b)=>a+b,0)/hums.length).toFixed(1) : '—';
  document.getElementById('repCount').textContent = filtered.length;

  // Render table
  const tbody = document.getElementById('repTableBody');
  if (tbody) {
    const recent = [...filtered].reverse().slice(0,50);
    tbody.innerHTML = recent.map(l=>`<tr>
      <td style="font-size:11px;font-family:var(--mono);color:var(--text3)">${fmtDate(l.ts)}</td>
      <td>${l.zone}</td>
      <td class="mono">${l.temperature}</td>
      <td class="mono">${l.humidity}</td>
      <td><span class="badge badge-${(l.fanStatus||'').toLowerCase()}">${l.fanStatus||'—'}</span></td>
      <td>${l.direction||'—'}</td>
    </tr>`).join('') || '<tr><td colspan="6" style="text-align:center;color:var(--text3);padding:24px">No data</td></tr>';
  }

  // Trend chart — group by hour
  const byHour = {};
  filtered.forEach(l => {
    const h = new Date(l.ts).getHours()+':00';
    if (!byHour[h]) byHour[h] = [];
    byHour[h].push(metric==='temperature' ? l.temperature : l.humidity);
  });
  const trendLabels = Object.keys(byHour).slice(-12);
  const trendData = trendLabels.map(h => { const arr=byHour[h]; return arr.length?(arr.reduce((a,b)=>a+b,0)/arr.length).toFixed(1):null; });

  const rctx = document.getElementById('reportChart');
  if (!rctx) return;
  if (reportChart) reportChart.destroy();
  reportChart = new Chart(rctx, {
    type:'line',
    data:{ labels:trendLabels, datasets:[{ label:metric==='temperature'?'Avg Temp (°C)':'Avg Humidity (%)', data:trendData, borderColor:'#4ade80', backgroundColor:'rgba(74,222,128,0.08)', fill:true, tension:0.4, pointRadius:4, borderWidth:2 }] },
    options:{ responsive:true, maintainAspectRatio:false, animation:false,
      plugins:{ legend:{ labels:{ color:'#8a95b0', font:{size:11} } } },
      scales:{ x:{ticks:{color:'#556070',font:{size:10}},grid:{color:'#2a3347'}},y:{ticks:{color:'#556070',font:{size:10}},grid:{color:'#2a3347'},beginAtZero:false} }
    }
  });

  // Zone comparison bar chart
  const zctx = document.getElementById('zoneChart');
  if (!zctx) return;
  if (zoneChart) zoneChart.destroy();
  const zoneAvgs = ZONES.map(z => {
    const zd = filtered.filter(l=>l.zone===z).map(l=>metric==='temperature'?l.temperature:l.humidity);
    return zd.length ? +(zd.reduce((a,b)=>a+b,0)/zd.length).toFixed(1) : 0;
  });
  zoneChart = new Chart(zctx, {
    type:'bar',
    data:{ labels:ZONES, datasets:[{ label:metric==='temperature'?'Avg Temp (°C)':'Avg Humidity (%)', data:zoneAvgs, backgroundColor:['rgba(74,222,128,0.4)','rgba(96,165,250,0.4)','rgba(251,191,36,0.4)','rgba(248,113,113,0.4)'], borderColor:['#4ade80','#60a5fa','#fbbf24','#f87171'], borderWidth:2, borderRadius:4 }] },
    options:{ responsive:true, maintainAspectRatio:false, animation:false,
      plugins:{ legend:{ labels:{ color:'#8a95b0', font:{size:11} } } },
      scales:{ x:{ticks:{color:'#556070',font:{size:10}},grid:{color:'#2a3347'}},y:{ticks:{color:'#556070',font:{size:10}},grid:{color:'#2a3347'},beginAtZero:false} }
    }
  });
}

function exportCSV() {
  if (currentUser.role==='viewer') { alert('Viewer cannot export data.'); return; }
  const logs = DB.get('sensorLogs') || [];
  if (!logs.length) { alert('No data to export.'); return; }
  const header = 'Timestamp,Zone,Temperature,Humidity,FanStatus,Direction';
  const rows = logs.map(l=>`"${l.ts}","${l.zone}","${l.temperature}","${l.humidity}","${l.fanStatus}","${l.direction}"`);
  download('coop_data_'+Date.now()+'.csv', header+'\n'+rows.join('\n'), 'text/csv');
  addAudit('Exported sensor data as CSV','export',currentUser.username);
}

function exportJSON() {
  if (currentUser.role==='viewer') { alert('Viewer cannot export data.'); return; }
  const logs = DB.get('sensorLogs') || [];
  download('coop_data_'+Date.now()+'.json', JSON.stringify({ exportedAt:now(), exportedBy:currentUser.username, count:logs.length, data:logs }, null, 2), 'application/json');
  addAudit('Exported sensor data as JSON','export',currentUser.username);
}

function download(filename, content, type) {
  const a = document.createElement('a');
  a.href = URL.createObjectURL(new Blob([content],{type}));
  a.download = filename; a.click();
}

// ═══════════════════════════════════════════
// USER MANAGEMENT MODULE
// ═══════════════════════════════════════════
function loadUsersPage() {
  const isAdmin = currentUser.role==='admin';
  document.getElementById('usersPageContent').style.display = isAdmin ? '' : 'none';
  document.getElementById('usersAccessDenied').style.display = isAdmin ? 'none' : 'block';
  if (!isAdmin) return;
  renderUsersTable();
  renderAuditLog();
}

function renderUsersTable() {
  const users = DB.get('users') || [];
  document.getElementById('totalUsers').textContent = users.length;
  document.getElementById('totalAdmins').textContent = users.filter(u=>u.role==='admin').length;
  const body = document.getElementById('usersTableBody');
  body.innerHTML = users.map(u=>`<tr>
    <td>${u.name}</td>
    <td class="mono" style="font-size:12px">${u.username}</td>
    <td><span class="badge badge-${u.role}">${u.role}</span></td>
    <td style="font-size:11px;color:var(--text3)">${u.createdAt ? fmtDate(u.createdAt) : '—'}</td>
    <td><span class="badge ${u.status==='active'?'badge-on':'badge-off'}">${u.status||'active'}</span></td>
    <td style="white-space:nowrap">
      <button class="btn-sm" onclick="openEditUser('${u.id}')">Edit</button>
      ${u.username!=='admin'?`<button class="btn-sm" style="color:var(--danger);border-color:var(--danger);margin-left:4px" onclick="deleteUser('${u.id}')">Del</button>`:''}
    </td>
  </tr>`).join('');
}

function openAddUserModal() { document.getElementById('addUserModal').classList.add('open'); }
function closeAddUserModal() { document.getElementById('addUserModal').classList.remove('open'); document.getElementById('addUserMsg').style.display='none'; }

function submitAddUser() {
  const name = document.getElementById('newUserName').value.trim();
  const username = document.getElementById('newUsername').value.trim();
  const pass = document.getElementById('newUserPass').value;
  const role = document.getElementById('newUserRole').value;
  const msg = document.getElementById('addUserMsg');
  if (!name||!username||!pass) { msg.textContent='All fields required'; msg.className='auth-msg error'; msg.style.display='block'; return; }
  if (pass.length<6) { msg.textContent='Password min 6 chars'; msg.className='auth-msg error'; msg.style.display='block'; return; }
  const users = DB.get('users') || [];
  if (users.find(u=>u.username===username)) { msg.textContent='Username taken'; msg.className='auth-msg error'; msg.style.display='block'; return; }
  users.push({ id:uid(), name, username, password:pass, role, status:'active', createdAt:now() });
  DB.set('users', users);
  addAudit(`Created user: ${username} (${role})`,'user_create',currentUser.username);
  closeAddUserModal();
  renderUsersTable();
  document.getElementById('newUserName').value=''; document.getElementById('newUsername').value=''; document.getElementById('newUserPass').value='';
}

function openEditUser(id) {
  const users = DB.get('users') || [];
  const u = users.find(x=>x.id===id);
  if (!u) return;
  document.getElementById('editUserTarget').value = id;
  document.getElementById('editUserName').value = u.name;
  document.getElementById('editUserRole').value = u.role;
  document.getElementById('editUserStatus').value = u.status||'active';
  document.getElementById('editUserModal').classList.add('open');
}
function closeEditUserModal() { document.getElementById('editUserModal').classList.remove('open'); }

function submitEditUser() {
  const id = document.getElementById('editUserTarget').value;
  const users = DB.get('users') || [];
  const u = users.find(x=>x.id===id);
  if (!u) return;
  u.name = document.getElementById('editUserName').value.trim();
  u.role = document.getElementById('editUserRole').value;
  u.status = document.getElementById('editUserStatus').value;
  DB.set('users', users);
  addAudit(`Updated user: ${u.username} → role=${u.role}, status=${u.status}`,'user_edit',currentUser.username);
  closeEditUserModal();
  renderUsersTable();
}

function deleteUser(id) {
  const users = DB.get('users') || [];
  const u = users.find(x=>x.id===id);
  if (!u || u.username===currentUser.username) { alert("Can't delete yourself."); return; }
  if (!confirm(`Delete user "${u.name}"?`)) return;
  DB.set('users', users.filter(x=>x.id!==id));
  addAudit(`Deleted user: ${u.username}`,'user_delete',currentUser.username);
  renderUsersTable();
}

// AUDIT LOG
function addAudit(msg, action, username) {
  const logs = DB.get('auditLog') || [];
  logs.unshift({ id:uid(), msg, action, username, ts:now() });
  if (logs.length>200) logs.splice(200);
  DB.set('auditLog', logs);
}

function renderAuditLog() {
  const logs = DB.get('auditLog') || [];
  const el = document.getElementById('auditLog');
  const ac = document.getElementById('auditCount');
  if (ac) ac.textContent = logs.length+' entries';
  if (!el) return;
  const colors = { login:'var(--accent)', logout:'var(--text3)', fan_control:'var(--info)', approve:'var(--accent)', reject:'var(--danger)', delete:'var(--danger)', transaction:'var(--info)', export:'var(--warn)', user_create:'var(--purple)', user_edit:'var(--purple)', user_delete:'var(--danger)', register:'var(--accent)' };
  if (!logs.length) { el.innerHTML = '<div style="text-align:center;padding:24px;color:var(--text3);font-size:12px;font-family:var(--mono)">No audit events</div>'; return; }
  el.innerHTML = logs.slice(0,50).map(l=>`
    <div class="log-row">
      <div class="log-time">${fmtDate(l.ts)}</div>
      <div class="log-dot" style="background:${colors[l.action]||'var(--text3)'}"></div>
      <div class="log-msg"><span class="log-user">${l.username}</span> — ${l.msg}</div>
    </div>`).join('');
}

// ═══════════════════════════════════════════
// BOOT
// ═══════════════════════════════════════════
initDB();

// Check existing session on load
(function() {
  const sess = DB.get('session');
  if (sess) {
    const users = DB.get('users') || [];
    const user = users.find(u=>u.id===sess.userId && u.status==='active');
    if (user) {
      currentUser = user;
      launchApp();
      return;
    }
  }
  document.getElementById('authScreen').style.display = 'flex';
})();
</script>
</body>
</html>