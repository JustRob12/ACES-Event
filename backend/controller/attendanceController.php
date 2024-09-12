<?php
use Ulid\Ulid;

require_once(__DIR__ . "/../util/header.php");
require_once(__DIR__ . "/../model/Attendance.php");

// Fetch request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Handle request
switch ($requestMethod) {

    case "GET": {
        if (isset($_GET["id"]) && !empty($_GET["id"])) {
            // Fetch attendance by id
            fetchAttendanceById($_GET["id"]);
        } else {
            fetchAttendance();
        }
        break;
    }
    case "POST" : {
        if(isset($_POST["method"]) && $_POST["method"] === "PATCH") {
            editAttendance($_GET["id"]);
        } else {
            insertAttendance();
        }
        break;
    }
    default: {
        $responseMessage = "Request method: {$requestMethod} not allowed!";
        response(false, ["message" => $responseMessage]);
        break;
    }
}

// Fetch all attendance records
function fetchAttendance()
{
    // Fetch all attendance records from database
    $attendance = readAttendance();
    // If no attendance records found, return false
    if (!$attendance) {
        response(false, ["message" => "No attendance records found"]);
        exit;
    }
    // Return all attendance records
    response(true, ["data" => $attendance]);
}

// Fetch single attendance record by ID
function fetchAttendanceById($id)
{
    $attendance = findAttendanceById($id);
    if (!$attendance) {
        response(false, ["message" => "Attendance record not found"]);
        exit;
    }

    response(true, $attendance);
}

// Insert a new attendance record
function insertAttendance()
{
    $id = Ulid::generate(true);
    $event_id = $_POST["event_id"];
    $student_id = $_POST["student_id"];
    $createdAt = $_POST["createdAt"];
    $checkIn = $_POST["checkIn"];
    $checkOut = $_POST["checkOut"];

    $data = [
        "id" => $id,
        "event_id" => $event_id,
        "student_id" => $student_id,
        "createdAt" => $createdAt,
        "checkIn" => $checkIn,
        "checkOut" => $checkOut
    ];

    $attendance = createAttendance($data);

    if (!$attendance) {
        response(false, ["message" => "Failed to create attendance record"]);
        exit;
    }
    response(true, ["message" => "Attendance record created"]);
}


function editAttendance($id)
{
  
    if (!isset($_POST["checkOut"])) {
        response(false, ["message" => "checkOut field is required"]);
        exit;
    }

    $checkOut = $_POST["checkOut"];

  
    $attendance = updateAttendance($id, $checkOut);

    if (!$attendance) {
        response(false, ["message" => "Failed to update attendance record"]);
        exit;
    }

    response(true, ["message" => "Attendance record updated"]);
}


// Delete an attendance record by ID
function removeAttendance($id)
{
    $attendance = deleteAttendance($id);

    if (!$attendance) {
        response(false, ["message" => "Failed to delete attendance record"]);
        exit;
    }
    response(true, ["message" => "Attendance record removed"]);
}

