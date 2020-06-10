<?php
$servername = "d82651.mysql.zonevs.eu";
$username = "d82651_benutzer";
$password = "K0llaneLabaku5";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";