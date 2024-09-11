<?php
// fetch_student.php

require 'db_connect.php'; // Ensure this file connects to your database

header('Content-Type: application/json');

if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Check if student exists
    $stmt = $con->prepare("SELECT * FROM tbl_student WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode([
            'status' => 'success',
            'firstname' => $student['firstname'],
            'middlename' => $student['middlename'],
            'lastname' => $student['lastname'],
            'course' => $student['course'],
            'year' => $student['year'],
            'profile' => 'data:image/jpeg;base64,' . base64_encode($student['profile'])
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Student not found.']);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No student ID provided.']);
}
?>
