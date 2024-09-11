<?php
// Include database connection
require 'db_connect.php'; // Update this path to your database connection file

// Get the scanned QR code value from the request
if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Prepare a SQL statement to fetch student information
    $stmt = $con->prepare("SELECT firstname, middlename, lastname, course, year, gmail, profile FROM tbl_student WHERE student_id = ?");
    
    if ($stmt === false) {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to prepare statement.'));
        exit();
    }

    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->store_result();
    
    // Check if any student was found
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($firstname, $middlename, $lastname, $course, $year, $gmail, $profile);
        $stmt->fetch();
        
        if (!empty($profile)) {
            // Convert the profile picture to a base64-encoded string
            $profile_base64 = base64_encode($profile);
            
            // Set the MIME type for the profile image (default to PNG)
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $profile_mime = finfo_buffer($finfo, $profile);
            finfo_close($finfo);
        } else {
            $profile_base64 = '';
            $profile_mime = 'image/png'; // Default MIME type if no image data is available
        }

        // Convert the profile picture to a base64-encoded string
        $profile_base64 = base64_encode($profile);
        $profile_mime = 'image/png'; // Adjust if necessary based on your image types

        // Create a response array
        $response = array(
            'status' => 'success',
            'firstname' => $firstname,
            'middlename' => $middlename,
            'lastname' => $lastname,
            'course' => $course,
            'year' => $year,
            'gmail' => $gmail,
            'profile' => !empty($profile_base64) ? 'data:' . $profile_mime . ';base64,' . $profile_base64 : '');
    } else {
        // No student found
        $response = array('status' => 'error', 'message' => 'No student found with this ID.');
    }

    $stmt->close();
    $con->close();

    // Output the response as JSON
    echo json_encode($response);
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No student ID provided.'));
}
?>
