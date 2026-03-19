<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>Smart Chicken Coop Dashboard - 4 Sensors</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
  font-family: Arial;
  background: #f4f6f9;
  margin: 0;
}

/* HEADER */
header {
  text-align: center;
  padding: 15px;
  background: #2c3e50;
  color: white;
}

/* MAIN LAYOUT */
.container {
  display: grid;
  grid-template-columns: 1fr 2fr 1fr;
  gap: 15px;
  padding: 15px;
}

/* SENSOR GRID */
#sensorsContainer {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
}

/* CARD */
.card {
  background: white;
  padding: 15px;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* VALUES */
.value {
  font-weight: bold;
  color: #27ae60;
}

/* BUTTONS */
button {
  padding: 6px 10px;
  margin: 3px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  font-size: 12px;
}

.on { background: green; color: white; }
.off { background: red; color: white; }

/* GRAPH */
canvas {
  width: 100%;
  height: 250px;
}
</style>
</head>

<body>

<header>
<h1>🐔 Smart Chicken Coop</h1>
<p>Admin Dashboard - 4 Sensors (Right, Left, Front, Back)</p>
</header>

<div class="container">

<!-- LEFT: GRAPH -->
<div class="card">
  <h2>📈 Environmental Graph</h2>
  <canvas id="sensorChart"></canvas>
</div>

<!-- CENTER: SENSORS -->
<div>
  <div id="sensorsContainer"></div>
</div>

<!-- RIGHT: STATUS -->
<div class="card">
  <h2>⚡ System Status</h2>
  <p><strong>System:</strong> Online</p>
  <p><strong>Last Update:</strong> <span id="lastUpdate">--</span></p>
</div>

</div>

<script>
// SENSOR CONFIG
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
  card.innerHTML = `
    <h3>${sensor.name}</h3>
    <p>Temp: <span class="value" id="temp${sensor.id}">--</span> °C</p>
    <p>Humidity: <span class="value" id="humidity${sensor.id}">--</span> %</p>

    <p>Fan: <strong id="fanStatus${sensor.id}">--</strong></p>

    <button class="on" onclick="turnOnFan(${sensor.id})">ON</button>
    <button class="off" onclick="turnOffFan(${sensor.id})">OFF</button>

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

// DIRECTION
function setDirection(id, dir){
  document.getElementById(`direction${id}`).innerText = dir;
  document.getElementById(`message${id}`).innerText = `Direction set to ${dir}`;
  updateDirectionDB(id,dir);
}

// TIME
function updateTime(){
  document.getElementById("lastUpdate").innerText = new Date().toLocaleTimeString();
}
setInterval(updateTime, 1000);

// GRAPH (IMPROVED)
const ctx = document.getElementById("sensorChart");

const sensorChart = new Chart(ctx,{
  type:"line",
  data:{
    labels:[],
    datasets:[
      {label:"Temp Right",data:[],borderColor:"red",backgroundColor:"rgba(255,0,0,0.1)",fill:true,tension:0.4,borderWidth:2},
      {label:"Hum Right",data:[],borderColor:"blue",backgroundColor:"rgba(0,0,255,0.1)",fill:true,tension:0.4,borderWidth:2},

      {label:"Temp Left",data:[],borderColor:"orange",backgroundColor:"rgba(255,165,0,0.1)",fill:true,tension:0.4,borderWidth:2},
      {label:"Hum Left",data:[],borderColor:"green",backgroundColor:"rgba(0,128,0,0.1)",fill:true,tension:0.4,borderWidth:2},

      {label:"Temp Front",data:[],borderColor:"purple",backgroundColor:"rgba(128,0,128,0.1)",fill:true,tension:0.4,borderWidth:2},
      {label:"Hum Front",data:[],borderColor:"cyan",backgroundColor:"rgba(0,255,255,0.1)",fill:true,tension:0.4,borderWidth:2},

      {label:"Temp Back",data:[],borderColor:"brown",backgroundColor:"rgba(165,42,42,0.1)",fill:true,tension:0.4,borderWidth:2},
      {label:"Hum Back",data:[],borderColor:"pink",backgroundColor:"rgba(255,192,203,0.1)",fill:true,tension:0.4,borderWidth:2},
    ]
  },

  options:{
    responsive:true,
    animation:false,

    plugins:{
      legend:{
        position:'bottom',
        labels:{font:{size:11}}
      },
      tooltip:{
        mode:'index',
        intersect:false
      }
    },

    interaction:{
      mode:'nearest',
      axis:'x',
      intersect:false
    },

    scales:{
      x:{grid:{display:false}},
      y:{grid:{color:"#ddd"}}
    }
  }
});

// FETCH DATA
function updateSensorData(){
  fetch('sensor_data_4.php')
    .then(res => res.json())
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
    });
}

// AUTO UPDATE
setInterval(updateSensorData,5000);
updateSensorData();

// DATABASE
function updateFanDB(id,status){
  fetch(`update_fan.php?id=${id}&status=${status}`);
}

function updateDirectionDB(id,direction){
  fetch(`update_direction.php?id=${id}&direction=${direction}`);
}
</script>

</body>
</html>