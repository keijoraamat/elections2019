<?php

$host = "d82651.mysql.zonevs.eu";
$user = "d82651sa302032";
$passw = "K0llaneLabaku5";
$database = "d82651sd340088";
$connect = mysqli_connect($host, $user, $passw, $database);

if (mysqli_connect_errno()) {
    echo "Ühendamine ebaõnnestus: " . mysqli_connect_error();
    exit();
} 
