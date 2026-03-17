<?php

include "db.php";

$query = "SELECT * FROM sensor_logs ORDER BY id DESC LIMIT 10";

$result = mysqli_query($conn,$query);

$data = [];

while($row=mysqli_fetch_assoc($result)){
    $data[]=$row;
}

echo json_encode($data);

?>