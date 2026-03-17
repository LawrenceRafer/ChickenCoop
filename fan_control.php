<?php

include "db.php";

$status = $_POST['status'];

$query = "UPDATE system_state SET fan_status='$status' WHERE id=1";

mysqli_query($conn,$query);

echo "Fan ".$status;

?>