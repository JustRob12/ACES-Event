<?php
// Include database connection
require 'db_connect.php';

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Fetch student details
    $stmt = $con->prepare("SELECT firstname, lastname, course, year FROM tbl_student WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'name' => $student['firstname'] . ' ' . $student['lastname'],
            'course' => $student['course'],
            'year' => $student['year']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false]);
}

$con->close();
?>
