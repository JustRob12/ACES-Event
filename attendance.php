<?php 
// Include the database connection
require 'db_connect.php';

// Include the header and navbar
include 'header.php';
include 'navbar.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_student'])) {
    $event_id = $_POST['event_id'];
    $student_id = $_POST['student_id'];

    // Check if the student exists in tbl_student
    $stmt = $con->prepare("SELECT * FROM tbl_student WHERE student_id = ?");
    $stmt->bind_param("s", $student_id,);
    $stmt->execute();
    $student_result = $stmt->get_result();

    if ($student_result->num_rows === 0) {
        // Student does not exist
        echo '<p style="color: red;">Error: Student ID does not exist.</p>';
    } else {
        // Check if the student is already registered for the event
        $stmt = $con->prepare("SELECT * FROM tbl_event_attendance WHERE event_id = ? AND student_id = ?");
        $stmt->bind_param("ss", $event_id, $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the existing record to check check-in/check-out status
            $attendance = $result->fetch_assoc();
            
            if ($attendance['checkin_time'] && !$attendance['checkout_time']) {
                // Student has already checked in but not checked out, so record check-out
                $stmt = $con->prepare("UPDATE tbl_event_attendance SET checkout_time = NOW() WHERE event_id = ? AND student_id = ?");
                $stmt->bind_param("ss", $event_id, $student_id);

                if ($stmt->execute()) {
                    echo '<p style="color: green;">Student checked out successfully!</p>';
                } else {
                    echo '<p style="color: red;">Error: ' . $stmt->error . '</p>';
                }
            } else if ($attendance['checkin_time'] && $attendance['checkout_time']) {
                // Student has already checked in and checked out, do not allow another check-in
                echo '<p style="color: red;">Student has already checked out.</p>';
            } else {
                // Student record exists but has not checked in yet, so record check-in
                $stmt = $con->prepare("UPDATE tbl_event_attendance SET checkin_time = NOW() WHERE event_id = ? AND student_id = ?");
                $stmt->bind_param("ss", $event_id, $student_id);

                if ($stmt->execute()) {
                    echo '<p style="color: green;">Student checked in successfully!</p>';
                } else {
                    echo '<p style="color: red;">Error: ' . $stmt->error . '</p>';
                }
            }
        } else {
            // Register the student and record check-in
            $stmt = $con->prepare("INSERT INTO tbl_event_attendance (event_id, student_id, checkin_time) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $event_id, $student_id);

            if ($stmt->execute()) {
                echo '<p style="color: green;">Student checked in successfully!</p>';
            } else {
                echo '<p style="color: red;">Error: ' . $stmt->error . '</p>';
            }
        }
    }
    $stmt->close();
}

// Fetch events for the dropdown
$events = $con->query("SELECT * FROM tbl_events");

// Handle event change and fetch students registered for the selected event
$selected_event_id = isset($_POST['event_id']) ? $_POST['event_id'] : '';

if ($selected_event_id) {
    // Modified query to join tbl_student with tbl_event_attendance to include check-in and check-out times
    $students = $con->prepare("
        SELECT s.student_id, s.firstname, s.lastname, s.course, s.year, ea.checkin_time, ea.checkout_time
        FROM tbl_student s
        JOIN tbl_event_attendance ea ON s.student_id = ea.student_id
        WHERE ea.event_id = ?
    ");
    $students->bind_param("s", $selected_event_id);
    $students->execute();
    $result = $students->get_result();
} else {
    $result = null;
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
   
        }
        .main-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .form-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .container {
            flex: 1;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group select,
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        #scanner-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #reader {
            width: 100%;
            max-width: 400px;
            border: 2px solid #007bff;
            border-radius: 4px;
        }
        #qr-result {
            margin-top: 20px;
            color: #007bff;
        }
        #show {
            display: none;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }
        #profile-picture {
            display: none;
            border-radius: 8px;
            border: 2px solid #007bff;
            width: 300px;
            height: 300px;
            object-fit: cover;
        }
        .info {
            flex: 1;
            text-align: left;
        }
        .info p {
            margin: 10px 0;
            font-size: 16px;
            line-height: 1.5;
        }
        h4 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: bold;
        }
        .search-container {
        margin-bottom: 15px;
    }
    #searchInput {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="form-container">
            <div class="container">
                <h2>Register Student for Event</h2>
                <form method="post" action="attendance.php">
                    <div class="form-group">
                        <label for="event_id">Select Event:</label>
                        <select id="event_id" name="event_id" required onchange="this.form.submit()">
                            <option value="">-- Select Event --</option>
                            <?php while ($event = $events->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($event['id']); ?>" <?php echo ($selected_event_id == $event['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($event['event_name']); ?>
                                </option>
                            <?php endwhile; ?>
                            </select>
                            </div>
                    <div class="form-group">
                        <label for="student_id">Student ID:</label>
                        <input type="text" id="student_id" name="student_id" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="register_student" value="Register Student">
                    </div>
                </form>
            </div>
            <div id="scanner-container">
            <h2>QR Code Scanner</h2>
            <div id="reader"></div>
            <div id="show">
                <img id="profile-picture" src="" alt="Profile Picture">
                <div class="info" id="result"></div>
            </div>
        </div>

            

        
    </div>
    <div class="container">
    <h2>Student List for Selected Event</h2>
    
    <!-- Search field for filtering student list -->
    <div class="search-container">
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search for Student ID...">
    </div>
    
    <?php if ($selected_event_id && $result && $result->num_rows > 0): ?>
        <table id="studentTable">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Check-In Time</th>
                    <th>Check-Out Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($student = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($student['course']); ?></td>
                        <td><?php echo htmlspecialchars($student['year']); ?></td>
                        <td><?php echo $student['checkin_time'] ? htmlspecialchars($student['checkin_time']) : 'N/A'; ?></td>
                        <td><?php echo $student['checkout_time'] ? htmlspecialchars($student['checkout_time']) : 'N/A'; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($selected_event_id): ?>
        <p>No students registered for this event yet.</p>
    <?php endif; ?>
</div>

<!-- JavaScript for filtering the table based on the search input -->
<script>
function filterTable() {
    // Get the value of the search input
    var input = document.getElementById("searchInput");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("studentTable");
    var tr = table.getElementsByTagName("tr");

    // Loop through all table rows, except for the first (headers)
    for (var i = 1; i < tr.length; i++) {
        var td = tr[i].getElementsByTagName("td")[0]; // Get the Student ID column
        if (td) {
            var txtValue = td.textContent || td.innerText;
            // Check if the row matches the search input
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = ""; // Show matching rows
            } else {
                tr[i].style.display = "none"; // Hide non-matching rows
            }
        }       
    }
}
</script>
    <script src="path/to/html5-qrcode.min.js"></script>
    <script src="./qrScript.js"></script>
    <script>
        const html5Qrcode = new Html5Qrcode('reader');

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            if (decodedText) {
                document.getElementById('student_id').value = decodedText;
                fetch('fetch_student.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        student_id: decodedText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Show profile picture and information
                        document.getElementById('profile-picture').src = data.profile;
                        document.getElementById('profile-picture').style.display = 'block'; // Show the profile picture
                        document.getElementById('result').innerHTML = `
                            <h4>Student Information</h4>
                            <p><strong>Name:</strong> ${data.firstname} ${data.middlename} ${data.lastname}</p>
                            <p><strong>Course:</strong> ${data.course}</p>
                            <p><strong>Year:</strong> ${data.year}</p>
                            <p><strong>Email:</strong> ${data.gmail}</p>
                        `;
                        document.getElementById('show').style.display = 'flex'; // Show the result

                        // Hide the information and reload the page after 5 seconds
                        // setTimeout(() => {
                        //     document.getElementById('show').style.display = 'none';
                        //     location.reload(); // Reload the page
                        // }, 5000);

                        html5Qrcode.stop();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing the QR code.');
                });
            }
        };

        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        html5Qrcode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
    </script>
</body>
</html>

