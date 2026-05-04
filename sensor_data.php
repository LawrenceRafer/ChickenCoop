<?php
header('Content-Type: application/json');

// Simulate 4 sensors
$sensors = [];
for($i=1;$i<=4;$i++){
    $sensors[] = [
        'temperature'=>rand(25,35),
        'humidity'=>rand(55,70),
        'fanStatus'=>rand(0,1)?"ON":"OFF",
        'direction'=>rand(0,1)?"IN":"OUT"
    ];
}
echo json_encode($sensors);
?>