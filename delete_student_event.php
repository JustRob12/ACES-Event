<?php
require 'db_connect.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    $stmt = $con->prepare("DELETE FROM tbl_event_attendance WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $stmt->close();

    echo 'Students deleted successfully';
}

$con->close();
?>
