<?php
require_once '../connect_to_db.php';

$state = filter_input(INPUT_GET, 'state', FILTER_VALIDATE_INT);

$sql_query = "SELECT *  FROM `kandidaadid`";
$database_response = mysqli_query($connect, $sql_query);
$row = mysqli_fetch_array($database_response, MYSQLI_ASSOC);
var_dump( $row);