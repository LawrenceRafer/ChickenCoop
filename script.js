let chart;

function loadLatest(){

fetch("get_latest.php")
.then(res=>res.json())
.then(data=>{

document.getElementById("temp").innerText = data.temperature
document.getElementById("humidity").innerText = data.humidity
document.getElementById("fanStatus").innerText = data.fan

})

}

function loadLogs(){

fetch("get_logs.php")
.then(res=>res.json())
.then(data=>{

let table=""

let temps=[]
let hums=[]
let labels=[]

data.reverse().forEach(row=>{

table += `
<tr>
<td>${row.created_at}</td>
<td>${row.temperature}</td>
<td>${row.humidity}</td>
<td>${row.fan_status}</td>
</tr>
`

labels.push(row.created_at)
temps.push(row.temperature)
hums.push(row.humidity)

})

document.getElementById("logTable").innerHTML = table

updateChart(labels,temps,hums)

})

}

function updateChart(labels,temps,hums){

if(chart) chart.destroy()

const ctx=document.getElementById("sensorChart")

chart=new Chart(ctx,{

type:"line",

data:{
labels:labels,
datasets:[

{
label:"Temperature",
data:temps,
borderColor:"red",
fill:false
},

{
label:"Humidity",
data:hums,
borderColor:"blue",
fill:false
}

]

}

})

}

function turnOnFan(){

fetch("fan_control.php",{

method:"POST",

headers:{
'Content-Type':'application/x-www-form-urlencoded'
},

body:"status=ON"

})
.then(res=>res.text())
.then(msg=>{

document.getElementById("message").innerText = msg

})

}

function turnOffFan(){

fetch("fan_control.php",{

method:"POST",

headers:{
'Content-Type':'application/x-www-form-urlencoded'
},

body:"status=OFF"

})
.then(res=>res.text())
.then(msg=>{

document.getElementById("message").innerText = msg

})

}

setInterval(()=>{

loadLatest()
loadLogs()

},3000)

loadLatest()
loadLogs()