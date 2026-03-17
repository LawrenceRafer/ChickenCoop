<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>Smart Chicken Coop Dashboard - 4 Sensors</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { font-family: Arial; background:#f4f6f9; margin:0; }
header { text-align:center; padding:20px; background:#2c3e50; color:white; }
.container { display:flex; gap:20px; padding:20px; }
.left-panel { flex:2; }
.right-panel { flex:1; }
.card { background:white; padding:20px; margin-bottom:20px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
.value { font-weight:bold; color:#27ae60; }
button { padding:10px 15px; margin:5px; border:none; cursor:pointer; border-radius:5px; }
.on { background:green; color:white; }
.off { background:red; color:white; }
table { width:100%; border-collapse:collapse; }
th, td { padding:8px; border-bottom:1px solid #ddd; text-align:center; }
canvas { width:100%; height:300px; }
</style>
</head>

<body>

<header>
<h1>🐔 Smart Chicken Coop System</h1>
<p>Admin Dashboard - 4 Sensors (Right, Left, Front, Back)</p>
</header>

<div class="container">

<!-- LEFT PANEL -->
<div class="left-panel">
<div id="sensorsContainer"></div>
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
</div>

</div>
</div>

<script>
// SENSOR CONFIG - Right, Left, Front, Back
const sensors = [
  {id:1,name:"Right"},
  {id:2,name:"Left"},
  {id:3,name:"Front"},
  {id:4,name:"Back"}
];

// CREATE SENSOR CARDS
const container = document.getElementById("sensorsContainer");
sensors.forEach(sensor => {
  const card = document.createElement("div");
  card.className = "card";
  card.id = `sensor${sensor.id}`;
  card.innerHTML = `
    <h2>🌡 ${sensor.name}</h2>
    <p>Temperature: <span class="value" id="temp${sensor.id}">--</span> °C</p>
    <p>Humidity: <span class="value" id="humidity${sensor.id}">--</span> %</p>
    <p>Fan Status: <strong id="fanStatus${sensor.id}">--</strong></p>
    <button class="on" onclick="turnOnFan(${sensor.id})">Turn ON Fan</button>
    <button class="off" onclick="turnOffFan(${sensor.id})">Turn OFF Fan</button>
    <p>Direction: <span id="direction${sensor.id}">--</span></p>
    <button onclick="setDirection(${sensor.id}, 'IN')">IN</button>
    <button onclick="setDirection(${sensor.id}, 'OUT')">OUT</button>
    <p id="message${sensor.id}"></p>
  `;
  container.appendChild(card);
});

// FAN CONTROL
function turnOnFan(id){
  document.getElementById(`fanStatus${id}`).innerText = "ON";
  document.getElementById(`message${id}`).innerText = "Fan turned ON";
  updateFanDB(id,"ON");
}
function turnOffFan(id){
  document.getElementById(`fanStatus${id}`).innerText = "OFF";
  document.getElementById(`message${id}`).innerText = "Fan turned OFF";
  updateFanDB(id,"OFF");
}

// DIRECTION CONTROL
function setDirection(id, dir){
  document.getElementById(`direction${id}`).innerText = dir;
  document.getElementById(`message${id}`).innerText = `Direction set to ${dir}`;
  updateDirectionDB(id,dir);
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
      {label:"Temp Right",data:[],borderColor:"red",fill:false},
      {label:"Hum Right",data:[],borderColor:"blue",fill:false},
      {label:"Temp Left",data:[],borderColor:"orange",fill:false},
      {label:"Hum Left",data:[],borderColor:"green",fill:false},
      {label:"Temp Front",data:[],borderColor:"purple",fill:false},
      {label:"Hum Front",data:[],borderColor:"cyan",fill:false},
      {label:"Temp Back",data:[],borderColor:"brown",fill:false},
      {label:"Hum Back",data:[],borderColor:"pink",fill:false},
    ]
  },
  options:{responsive:true,animation:false,scales:{y:{beginAtZero:false}}}
});

// FETCH SENSOR DATA
function updateSensorData(){
  fetch('sensor_data_4.php')
    .then(response => response.json())
    .then(data => {
      const now = new Date();
      const timeLabel = now.getHours() + ":" + String(now.getMinutes()).padStart(2,"0");
      sensorChart.data.labels.push(timeLabel);
      sensorChart.data.labels = sensorChart.data.labels.slice(-10);

      sensors.forEach((sensor,i)=>{
        document.getElementById(`temp${sensor.id}`).innerText = data[i].temperature;
        document.getElementById(`humidity${sensor.id}`).innerText = data[i].humidity;
        document.getElementById(`fanStatus${sensor.id}`).innerText = data[i].fanStatus;
        document.getElementById(`direction${sensor.id}`).innerText = data[i].direction;

        sensorChart.data.datasets[i*2].data.push(data[i].temperature);
        sensorChart.data.datasets[i*2].data = sensorChart.data.datasets[i*2].data.slice(-10);
        sensorChart.data.datasets[i*2+1].data.push(data[i].humidity);
        sensorChart.data.datasets[i*2+1].data = sensorChart.data.datasets[i*2+1].data.slice(-10);
      });
      sensorChart.update();
    })
    .catch(err => console.error("Error fetching sensor data:", err));
}

// Update every 5 seconds
setInterval(updateSensorData,5000);
updateSensorData();

// AJAX DB UPDATES
function updateFanDB(id,status){
  fetch(`update_fan.php?id=${id}&status=${status}`);
}
function updateDirectionDB(id,direction){
  fetch(`update_direction.php?id=${id}&direction=${direction}`);
}
</script>

</body>
</html>