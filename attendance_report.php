<?php 
// Include the database connection
require 'db_connect.php';

// Include the header and navbar
include 'header.php';
include 'navbar.php';

// Fetch events for the dropdown
$events_query = "SELECT id, event_name FROM tbl_events";
$events_result = $con->query($events_query);

// Fetch courses for the dropdown
$courses_query = "SELECT DISTINCT course FROM tbl_student";
$courses_result = $con->query($courses_query);

// Initialize variables
$selected_event_id = isset($_POST['event_id']) ? $_POST['event_id'] : null;
$selected_course = isset($_POST['course']) ? $_POST['course'] : null;
$attendance_records = [];
$total_count = 0;

// Fetch attendance records if an event is selected
if ($selected_event_id) {
    $query = "
        SELECT a.student_id, s.firstname, s.lastname, s.course, s.year, a.checkin_time, a.checkout_time 
        FROM tbl_event_attendance a
        JOIN tbl_student s ON a.student_id = s.student_id
        WHERE a.event_id = ?";
    
    // Add course filtering if selected
    if ($selected_course) {
        $query .= " AND s.course = ?";
    }

    $stmt = $con->prepare($query);
    
    // Bind parameters
    if ($selected_course) {
        $stmt->bind_param("is", $selected_event_id, $selected_course);
    } else {
        $stmt->bind_param("i", $selected_event_id);
    }

    $stmt->execute();
    $attendance_records = $stmt->get_result();
    $total_count = $attendance_records->num_rows;
    $stmt->close();
}

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .filter-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .filter-form select, .filter-form button {
            padding: 10px;
            font-size: 16px;
        }
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .attendance-table th, .attendance-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .attendance-table th {
            background-color: #ff9800;
            color: #fff;
        }
        .attendance-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-count {
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 18px;
            color: #ff9800;
        }
        .print-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff9800;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .print-button:hover {
            background-color: #e68a00;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .printable-area, .printable-area * {
                visibility: visible;
            }
            .printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
            }
            .filter-form, .print-button {
                display: none;
            }
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Generate Attendance Report</h2>

    <!-- Event and Course Selection Form -->
    <form method="post" action="attendance_report.php" class="filter-form">
        <label for="event_id">Select Event:</label>
        <select name="event_id" id="event_id" required>
            <option value="">-- Select an Event --</option>
            <?php while ($event = $events_result->fetch_assoc()): ?>
                <option value="<?php echo $event['id']; ?>" <?php if ($selected_event_id == $event['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($event['event_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="course">Select Course:</label>
        <select name="course" id="course">
            <option value="">-- All Course --</option>
            <?php while ($course = $courses_result->fetch_assoc()): ?>
                <option value="<?php echo $course['course']; ?>" <?php if ($selected_course == $course['course']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($course['course']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Generate Report</button>
    </form>

    <!-- Attendance Report Table -->
    <div class="printable-area">
        <?php if ($selected_event_id && $attendance_records->num_rows > 0): ?>
            <p class="total-count">Total Students: <?php echo $total_count; ?></p>
            <table class="attendance-table">
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
                    <?php while ($record = $attendance_records->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($record['firstname'] . ' ' . $record['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($record['course']); ?></td>
                            <td><?php echo htmlspecialchars($record['year']); ?></td>
                            <td><?php echo $record['checkin_time'] ? htmlspecialchars($record['checkin_time']) : 'N/A'; ?></td>
                            <td><?php echo $record['checkout_time'] ? htmlspecialchars($record['checkout_time']) : 'N/A'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif ($selected_event_id): ?>
            <p>No attendance records found for the selected event.</p>
        <?php endif; ?>
    </div>

    <!-- Print Button -->
    <button class="print-button" onclick="printReport()">Print Report</button>
</div>

</body>
</html>
