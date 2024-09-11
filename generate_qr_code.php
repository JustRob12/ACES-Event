<?php
// Include database connection
require 'db_connect.php';
include 'header.php'; // Include your existing header file

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Fetch student information from the database
    $stmt = $con->prepare("SELECT * FROM tbl_student WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($student) {
        // Create QR code data
        $qr_code_data = $student['student_id'];

        // Generate base64 image data for profile picture
        $profile_pic = $student['profile'];
        $profile_img = '<img src="data:image/jpeg;base64,' . base64_encode($profile_pic) . '" alt="Profile Picture" class="profile-img">';

        // Generate QR code
        echo '<script src="qrcode.min.js"></script>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var qrcode = new QRCode(document.getElementById("qrcode"), {
                    text: "' . $qr_code_data . '",
                    width: 150,
                    height: 150
                });
            });
        </script>';

        // HTML and CSS for the redesigned ID card
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>ID Card</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #eceff1; /* Light grey background */
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }

                #id-card {
                    width: 350px;
                    height: 500px;
                    border: 1px solid #ddd;
                    border-radius: 10px;
                    background: linear-gradient(to bottom right, #FFA500, #FF4500);
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    box-sizing: border-box;
                    text-align: center;
                    position: relative;
                    color: #fff;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }

                .logo-container {
                    text-align: center;
                    margin-bottom: 10px;
                }

                .aces-logo {
                    width: 80px;
                    height: auto;
                    margin: 0 auto;
                }

                .profile-container {
                    margin: 10px 0;
                }

                .profile-img {
                    width: 100px;
                    height: 100px;
                    border: 2px solid #ddd;
                    object-fit: cover; /* Square profile picture */
                    margin: 0 auto;
                }

                h2 {
                    margin: 10px 0 5px;
                    font-size: 20px;
                    color: #fff;
                }

                .details {
                    font-size: 14px;
                    margin: 3px 0;
                }

                #qrcode {
                    margin: 0 auto;
                }

                #download-btn {
                    display: inline-block;
                    margin-top: 15px;
                    padding: 10px 15px;
                    background-color: #007bff;
                    color: #fff;
                    border: none;
                    border-radius: 5px;
                    text-decoration: none;
                    font-size: 14px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }

                #download-btn:hover {
                    background-color: #0056b3;
                }

                .error {
                    color: red;
                    text-align: center;
                    margin-top: 20px;
                }

                #download-container {
                    text-align: center;
                    margin-top: 15px;
                }
            </style>
        </head>
        <body>
            <div id="id-card">
                <div class="logo-container">
                    <img src="ACES LOGO.png" alt="ACES Logo" class="aces-logo">
                </div>
                <div class="profile-container">' . $profile_img . '</div>
                <h2>' . htmlspecialchars($student['firstname']) . ' ' . htmlspecialchars($student['lastname']) . '</h2>
                <p class="details">Course: ' . htmlspecialchars($student['course']) . '</p>
                <p class="details">Year: ' . htmlspecialchars($student['year']) . '</p>
                <div id="qrcode"></div>
            </div>
            <div id="download-container">
                <a href="#" id="download-btn">Download ID Card as Image</a>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
            <script>
                document.getElementById("download-btn").addEventListener("click", function(event) {
                    event.preventDefault();
                    var idCard = document.getElementById("id-card");
                    
                    html2canvas(idCard).then(function(canvas) {
                        canvas.toBlob(function(blob) {
                            var link = document.createElement("a");
                            link.href = URL.createObjectURL(blob);
                            link.download = "' . htmlspecialchars($student['student_id']) . '-id-card.png";
                            link.click();
                        });
                    }).catch(function(error) {
                        console.error("Error generating the image:", error);
                    });
                });
            </script>
        </body>
        </html>';
    } else {
        echo '<p class="error">Student not found.</p>';
    }
} else {
    echo '<p class="error">No student ID provided.</p>';
}

$con->close();
?>
