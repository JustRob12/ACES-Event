<?php
require_once(__DIR__ . "/../database/database.php");

use database\Database;

$attendanceTable = "attendance";
$database = new Database();

function readAttendance()
{
    global $attendanceTable;
    global $database;

    $query = "SELECT id, event_id, student_id, createdAt, checkIn, checkOut FROM $attendanceTable";
    $stmt = $database->connect()->prepare($query);
    $stmt->execute();

    $rowCount = $stmt->rowCount();
    if ($rowCount == 0) {
        return null;
    }

    $result = $stmt->fetchAll();
    return $result;
}

function findAttendanceById($id)
{
    global $attendanceTable;
    global $database;

    $query = "SELECT * FROM $attendanceTable WHERE id = :attendanceId";
    $stmt = $database->connect()->prepare($query);

    $stmt->bindParam(":attendanceId", $id);
    $stmt->execute();

    $result = $stmt->fetch();
    return $result;
}

function createAttendance(array $attendance)
{
    global $attendanceTable;
    global $database;

    $query = "INSERT INTO $attendanceTable (id, event_id, student_id, createdAt, checkIn, checkOut) 
              VALUES (:id, :event_id, :student_id, :createdAt, :checkIn, :checkOut)";

    $stmt = $database->connect()->prepare($query);

    $stmt->bindValue(":id", $attendance["id"]);
    $stmt->bindValue(":event_id", $attendance["event_id"]);
    $stmt->bindValue(":student_id", $attendance["student_id"]);
    $stmt->bindValue(":createdAt", $attendance["createdAt"]);
    $stmt->bindValue(":checkIn", $attendance["checkIn"]);
    $stmt->bindValue(":checkOut", $attendance["checkOut"]);

    return $stmt->execute();
}

function updateAttendance($id, $checkOut)
{
    global $attendanceTable;
    global $database;

    $query = "UPDATE $attendanceTable 
              SET checkOut = :checkOut 
              WHERE id = :id";

    $stmt = $database->connect()->prepare($query);

    $stmt->bindValue(":id", $id);
    $stmt->bindValue(":checkOut", $checkOut);

    return $stmt->execute();
}

function deleteAttendance($id)
{
    global $attendanceTable;
    global $database;

    $query = "DELETE FROM $attendanceTable WHERE id = :id";

    $stmt = $database->connect()->prepare($query);

    $stmt->bindValue(":id", $id);

    return $stmt->execute();
}

