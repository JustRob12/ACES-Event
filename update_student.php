<?php
// Include database connection
require 'db_connect.php'; // Update this path to your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $student_id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $gmail = $_POST['email'];

    // Validate email
    if (!filter_var($gmail, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='error'>Invalid email address. Please enter a valid email.</p>";
    } else {
        // Handle profile picture update
        if (isset($_FILES['profile']) && $_FILES['profile']['error'] == 0) {
            $file_tmp = $_FILES['profile']['tmp_name'];
            $file_type = $_FILES['profile']['type'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif']; // Allowed file types

            if (in_array($file_type, $allowed)) {
                $profile = file_get_contents($file_tmp); // Get the image as BLOB data

                // Update the database with new data including profile picture
                $stmt = $con->prepare("UPDATE tbl_student SET firstname = ?, middlename = ?, lastname = ?, course = ?, year = ?, gmail = ?, profile = ? WHERE student_id = ?");
                $stmt->bind_param("ssssssss", $firstname, $middlename, $lastname, $course, $year, $gmail, $profile, $student_id);

                // Use send_long_data() for the BLOB data
                $stmt->send_long_data(6, $profile);
            } else {
                echo "<p class='error'>Please upload a valid image file (JPEG, PNG, or GIF).</p>";
                exit;
            }
        } else {
            // Update without changing the profile picture
            $stmt = $con->prepare("UPDATE tbl_student SET firstname = ?, middlename = ?, lastname = ?, course = ?, year = ?, gmail = ? WHERE student_id = ?");
            $stmt->bind_param("sssssss", $firstname, $middlename, $lastname, $course, $year, $gmail, $student_id);
        }

        if ($stmt->execute()) {
            header("Location: ShowDisplayProfile.php?student_id=" . urlencode($student_id) . "&success=1");
            exit;
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    $con->close();
}
?>
