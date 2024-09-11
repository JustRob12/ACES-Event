<?php
// Include database connection
include 'db_connect.php'; // Make sure this file contains the correct connection details

// Check if the required POST data is available
if (isset($_POST['student_id']) && isset($_POST['firstname']) && isset($_POST['middlename']) &&
    isset($_POST['lastname']) && isset($_POST['course']) && isset($_POST['year']) &&
    isset($_POST['email']) && isset($_POST['scan_time'])) {

    // Sanitize and prepare data
    $student_id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $email = $_POST['email'];
    $scan_time = $_POST['scan_time'];

    // Create a prepared statement
    $stmt = $conn->prepare("INSERT INTO tbl_attendance_students (student_id, firstname, middlename, lastname, course, year, email, scantime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Bind parameters
    $stmt->bind_param("ssssssss", $student_id, $firstname, $middlename, $lastname, $course, $year, $email, $scan_time);

    // Execute the statement
    if ($stmt->execute()) {
        // Return a success response
        echo json_encode(["status" => "success"]);
    } else {
        // Return an error response
        echo json_encode(["status" => "error", "message" => "Failed to insert record"]);
    }

    // Close the statement
    $stmt->close();
} else {
    // Return an error response if required data is missing
    echo json_encode(["status" => "error", "message" => "Missing required parameters"]);
}

// Close the database connection
$conn->close();
?>
