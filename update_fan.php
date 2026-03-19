<?php
include "db.php";

$id = $_GET['id'];
$status = $_GET['status'];

$query = "UPDATE system_state SET fan_status='$status' WHERE sensor_id='$id'";
mysqli_query($conn,$query);
?>