<?php
include "db.php";

$id = $_GET['id'];
$dir = $_GET['direction'];

$query = "UPDATE system_state SET direction='$dir' WHERE sensor_id='$id'";
mysqli_query($conn,$query);
?>