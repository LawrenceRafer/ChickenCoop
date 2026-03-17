<?php

include "db.php";

$query = "SELECT * FROM sensor_logs ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn,$query);

$row = mysqli_fetch_assoc($result);

$fanQuery = "SELECT fan_status FROM system_state WHERE id=1";
$fanResult = mysqli_query($conn,$fanQuery);
$fanRow = mysqli_fetch_assoc($fanResult);

$data = [
    "temperature"=>$row['temperature'],
    "humidity"=>$row['humidity'],
    "fan"=>$fanRow['fan_status']
];

echo json_encode($data);

?>