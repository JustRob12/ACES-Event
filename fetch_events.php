<?php
require 'db_connect.php';

$date = $_GET['date'];

$sql = "SELECT * FROM tbl_events WHERE event_date = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$con->close();

header('Content-Type: application/json'); // Ensure correct content type
echo json_encode($events);
?>
