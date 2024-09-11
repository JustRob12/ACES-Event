<?php
$host = "localhost";
$port = 3306;
$socket = "";
$user = "root";
$password = "iloveyou"; 
$dbname = "aces_system";

// Create a connection
$con = new mysqli($host, $user, $password, $dbname, $port, $socket);

// Check connection
if ($con->connect_error) {
    die('Could not connect to the database server: ' . $con->connect_error);
}

// Uncomment the following line if you want to close the connection immediately
// $con->close();
?>
