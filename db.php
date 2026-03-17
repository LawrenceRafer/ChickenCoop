<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "chicken_coop";

$conn = mysqli_connect($host,$user,$password,$database);

if(!$conn){
    die("Database connection failed");
}

?>