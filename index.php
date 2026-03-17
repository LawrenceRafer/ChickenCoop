<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>Smart Chicken Coop Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body{font-family: Arial;background:#f4f6f9;margin:0;}
header{text-align:center;padding:20px;background:#2c3e50;color:white;}
.container{display:flex;gap:20px;padding:20px;}
.left-panel{flex:2;}
.right-panel{flex:1;}
.card{background:white;padding:20px;margin-bottom:20px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}
.value{font-weight:bold;color:#27ae60;}
button{padding:10px 15px;margin:5px;border:none;cursor:pointer;border-radius:5px;}
.on{background:green;color:white;}
.off{background:red;color:white;}
table{width:100%;border-collapse:collapse;}
th,td{padding:8px;border-bottom:1px solid #ddd;text-align:center;}
canvas{width:100%;height:300px;}
</style>
</head>

<body>

<header>
<h1>🐔 Smart Chicken Coop System</h1>
<p>Admin Dashboard</p>
</header>

<div class="container">

<!-- LEFT PANEL -->
<div class="left-panel">

<!-- SENSOR STATUS -->
<div class="card">
<h2>🌡 Sensor Monitoring</h2>
<p>Temperature: <span class="value" id="temp">--</span> °C</p>
<p>Humidity: <span class="value" id="humidity">--</span> %</p>
</div>

<!-- FAN CONTROL -->
<div class="card">
<h2>🌀 Ventilation Control</h2>
<p>Fan Status: <strong id="fanStatus">--</strong></p>
<button class="on" onclick="turnOnFan()">Turn ON Fan</button>
<button class="off" onclick="turnOffFan()">Turn OFF Fan</button>
<p id="message"></p>
</div>

<!-- SYSTEM LOGS -->
<div class="card">
<h2>📊 System Logs</h2>
<table>
<thead>
<tr>
<th>Date & Time</th>
<th>Temperature</th>
<th>Humidity</th>
<th>Fan</th>
</tr>
</thead>
<tbody id="logTable">
<tr>
<td>2026-03-17 10:20</td>
<td>30</td>
<td>65</td>
<td>OFF</td>
</tr>
<tr>
<td>2026-03-17 10:15</td>
<td>29</td>
<td>63</td>
<td>ON</td>
</tr>
</tbody>
</table>
</div>

</div>

<!-- RIGHT PANEL -->
<div class="right-panel">

<!-- ENVIRONMENT GRAPH -->
<div class="card">
<h2>📈 Environmental Graph</h2>
<canvas id="sensorChart"></canvas>
</div>

<!-- SYSTEM STATUS -->
<div class="card">
<h2>⚡ System Status</h2>
<p><strong>System:</strong> Online</p>
<p><strong>Last Update:</strong> <span id="lastUpdate">--</span></p>
<p><strong>Fan Mode:</strong> <span id="fanMode">Manual</span></p>
</div>

</div>
</div>

<script>
// FAN CONTROL
function turnOnFan(){
  document.getElementById("fanStatus").innerText = "ON";
  document.getElementById("message").innerText = "Fan turned ON";
}
function turnOffFan(){
  document.getElementById("fanStatus").innerText = "OFF";
  document.getElementById("message").innerText = "Fan turned OFF";
}

// UPDATE TIME
function updateTime(){
  document.getElementById("lastUpdate").innerText = new Date().toLocaleTimeString();
}
setInterval(updateTime, 1000);

// CHART SETUP
const ctx = document.getElementById("sensorChart");
const sensorChart = new Chart(ctx,{
  type:"line",
  data:{
    labels:[],
    datasets:[
      {label:"Temperature",data:[],borderColor:"red",fill:false},
      {label:"Humidity",data:[],borderColor:"blue",fill:false}
    ]
  },
  options:{responsive:true,animation:false,scales:{y:{beginAtZero:false}}}
});

// FETCH SENSOR DATA
function updateSensorData(){
  fetch('sensor_data.php')
    .then(response => response.json())
    .then(data => {
      document.getElementById("temp").innerText = data.temperature;
      document.getElementById("humidity").innerText = data.humidity;
      document.getElementById("fanStatus").innerText = data.fanStatus;

      const now = new Date();
      const timeLabel = now.getHours() + ":" + String(now.getMinutes()).padStart(2,"0");

      sensorChart.data.labels.push(timeLabel);
      sensorChart.data.labels = sensorChart.data.labels.slice(-10); // last 10 points

      sensorChart.data.datasets[0].data.push(data.temperature);
      sensorChart.data.datasets[0].data = sensorChart.data.datasets[0].data.slice(-10);

      sensorChart.data.datasets[1].data.push(data.humidity);
      sensorChart.data.datasets[1].data = sensorChart.data.datasets[1].data.slice(-10);

      sensorChart.update();
    })
    .catch(err => console.error("Error fetching sensor data:", err));
}

// Update every 5 seconds
setInterval(updateSensorData,5000);
updateSensorData();
</script>

</body>
</html>