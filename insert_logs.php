<?php

include "db.php";

$temp = $_POST['temperature'];
$humidity = $_POST['humidity'];
$fan = $_POST['fan_status'];

$sql = "INSERT INTO sensor_logs (temperature,humidity,fan_status)
VALUES ('$temp','$humidity','$fan')";

mysqli_query($conn,$sql);

echo "OK";

?>