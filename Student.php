<?php
// Include database connection, header, and navbar
require 'db_connect.php';
include 'header.php'; // Assumes header.php includes the header and necessary styles/scripts
include 'navbar.php'; // Assumes navbar.php contains the navbar with orange background

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $student_id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $gmail = $_POST['gmail'];

    // Validate email
    if (!filter_var($gmail, FILTER_VALIDATE_EMAIL) || strpos($gmail, '@') === false) {
        echo "<p class='error'>Invalid email address. Please enter a valid email containing '@'.</p>";
    } else {
        // Check if the student ID already exists
        $check_stmt = $con->prepare("SELECT COUNT(*) FROM tbl_student WHERE student_id = ?");
        $check_stmt->bind_param("s", $student_id);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            echo "<p class='error'>Student ID already exists. Please enter a different Student ID.</p>";
        } else {
            // Check if a file was uploaded and it's an image
            if (isset($_FILES['profile']) && $_FILES['profile']['error'] == 0) {
                $file_tmp = $_FILES['profile']['tmp_name'];
                $file_type = $_FILES['profile']['type'];
                $allowed = ['image/jpeg', 'image/png', 'image/gif']; // Allowed file types

                if (in_array($file_type, $allowed)) {
                    $profile = file_get_contents($file_tmp); // Get the image as BLOB data

                    // Insert into the database using a prepared statement
                    $stmt = $con->prepare("INSERT INTO tbl_student (student_id, firstname, middlename, lastname, course, year, gmail, profile) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                    // Use 's' for all string types, and 'b' for the BLOB (profile image)
                    $stmt->bind_param("ssssssss", $student_id, $firstname, $middlename, $lastname, $course, $year, $gmail, $profile);

                    // Use send_long_data() for the BLOB data
                    $stmt->send_long_data(7, $profile);

                    if ($stmt->execute()) {
                        echo "<p class='success'>Student registered successfully!</p>";
                    } else {
                        echo "<p class='error'>Error: " . $stmt->error . "</p>";
                    }

                    $stmt->close();
                } else {
                    echo "<p class='error'>Please upload a valid image file (JPEG, PNG, or GIF).</p>";
                }
            } else {
                echo "<p class='error'>Please upload a profile picture.</p>";
            }
        }
    }
}

$con->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Student</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f7f7f7;
        }

        nav {
            width: 100%; /* Ensure navbar takes full width */
            background-color: #fc6900;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px; /* Add margin to separate from content */
        }

        .container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: 700;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="file"], select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            background-color: #fc6900;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #e05a00;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register a Student</h2>
        <form action="Student.php" method="post" enctype="multipart/form-data">
            <label for="student_id">Student ID:</label>
            <input type="text" name="student_id" id="student_id" required>

            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" id="firstname" required>

            <label for="middlename">Middle Name:</label>
            <input type="text" name="middlename" id="middlename" >

            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" id="lastname" required>

            <label for="course">Course:</label>
            <select name="course" id="course" required>
                <option value="">Select Course</option>
                <option value="BSIT">BSIT</option>
                <option value="BSCE">BSCE</option>
                <option value="BITM">BITM</option>
                <option value="BSM">BSM</option>
            </select>


            <label for="year">Year:</label>
            <select name="year" id="year" required>
                <option value="">Select Year</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
            </select>

            <label for="gmail">Gmail:</label>
            <input type="email" name="gmail" id="gmail" required>

            <label for="profile">Profile Picture:</label>
            <input type="file" name="profile" id="profile" accept="image/*" required>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
