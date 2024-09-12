<?php
require_once(__DIR__ . "/../database/database.php");

use database\Database;

$attendanceTable = "attendance";
$database = new Database();

function readAttendance()
{
    // Access variables outside the function
    global $attendanceTable;
    global $database;

    // Query statement
    $query = "SELECT id, event_id, student_id, createdAt, checkIn, checkOut FROM $attendanceTable";
    // Perform connection and execute
    $stmt = $database->connect()->prepare($query);
    $stmt->execute();

    // Count return data rows
    $rowCount = $stmt->rowCount();
    // Return null if no results
    if ($rowCount == 0) {
        return null;
    }
    // Fetch results
    $result = $stmt->fetchAll();
    return $result;
}

function findAttendanceById($id)
{
    global $attendanceTable;
    global $database;

    $query = "SELECT * FROM $attendanceTable WHERE id = :attendanceId";

    // Perform connection and execute
    $stmt = $database->connect()->prepare($query);

    // Bind data
    $stmt->bindParam(":attendanceId", $id);
    $stmt->execute();

    // Return attendance
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

    // Bind values
    $stmt->bindValue(":id", $attendance["id"]);
    $stmt->bindValue(":event_id", $attendance["event_id"]);
    $stmt->bindValue(":student_id", $attendance["student_id"]);
    $stmt->bindValue(":createdAt", $attendance["createdAt"]);
    $stmt->bindValue(":checkIn", $attendance["checkIn"]);
    $stmt->bindValue(":checkOut", $attendance["checkOut"]);

    // Execute the prepared statement
    return $stmt->execute();
}

function updateAttendance($id, array $attendance)
{
    global $attendanceTable;
    global $database;

    $query = "UPDATE $attendanceTable 
                SET event_id = :event_id, student_id = :student_id, createdAt = :createdAt, checkIn = :checkIn, checkOut = :checkOut 
                WHERE id = :id";

    $stmt = $database->connect()->prepare($query);

    // Bind values
    $stmt->bindValue(":id", $id);
    $stmt->bindValue(":event_id", $attendance["event_id"]);
    $stmt->bindValue(":student_id", $attendance["student_id"]);
    $stmt->bindValue(":createdAt", $attendance["createdAt"]);
    $stmt->bindValue(":checkIn", $attendance["checkIn"]);
    $stmt->bindValue(":checkOut", $attendance["checkOut"]);

    // Execute the prepared statement
    return $stmt->execute();
}

function deleteAttendance($id)
{
    global $attendanceTable;
    global $database;

    $query = "DELETE FROM $attendanceTable WHERE id = :id";

    $stmt = $database->connect()->prepare($query);

    // Bind value
    $stmt->bindValue(":id", $id);

    // Execute the prepared statement
    return $stmt->execute();
}
?>
