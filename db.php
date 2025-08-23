<?php
$servername = "localhost"; 
$username   = "root"; 
$password   = ""; // kung may password, ilagay dito
$dbname     = "hotel_db"; 
$port       = 3307; // default port

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
