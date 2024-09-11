<?php
// Include database connection
require 'db_connect.php';

// Include QR code library
// Make sure the path to your local qrcode.min.js is correct
// Example: 'path_to/qrcode.min.js'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate QR Code</title>
    <script src="qrcode.min.js"></script> <!-- Include your local QR code library -->
</head>
<body>
<?php
// Check if student_id is provided
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    
    // Fetch student information from the database
    $stmt = $con->prepare("SELECT * FROM tbl_student WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    
    if ($student) {
        // Generate QR code data (only student_id)
        $qr_code_data = $student['student_id'];
        
        echo '<h1>QR Code for Student ID: ' . htmlspecialchars($student['student_id']) . '</h1>';
        echo '<div id="qrcode"></div>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var qrcode = new QRCode(document.getElementById("qrcode"), {
                    text: ' . json_encode($qr_code_data) . ',
                    width: 300,
                    height: 300
                });
            });
        </script>';
    } else {
        echo '<p class="error">Student not found.</p>';
    }
} else {
    echo '<p class="error">No student ID provided.</p>';
}

$con->close();
?>
</body>
</html>
