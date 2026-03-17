<?php
// Replace with actual sensor reads in real implementation
$temperature = rand(25, 35); // Simulated temperature
$humidity = rand(55, 70);    // Simulated humidity
$fanStatus = rand(0,1) ? "ON" : "OFF"; // Simulated fan status

header('Content-Type: application/json');
echo json_encode([
    'temperature' => $temperature,
    'humidity' => $humidity,
    'fanStatus' => $fanStatus
]);
?>