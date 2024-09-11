<?php 
// Include the database connection
require 'db_connect.php';

// Include the header and navbar
include 'header.php';
include 'navbar.php';

// Fetch students
$students_query = "SELECT * FROM tbl_student";
$students_result = $con->query($students_query);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];

    // Check if student exists
    $stmt = $con->prepare("SELECT * FROM tbl_student WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            justify-content: center;
        }
        .container {
            display: flex;
            width: 1000px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .input-container,
        .list-container {
            width: 50%;
            padding: 10px;
        }
        .input-container {
            border-right: 1px solid #ddd;
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
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .no-records {
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="input-container">
            <h2>Check Student Attendance</h2>
            <form method="post" action="attendance_list.php">
                <div class="form-group">
                    <label for="student_id">Enter Student ID:</label>
                    <input type="text" id="student_id" name="student_id" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Search Student">
                </div>
            </form>
            <?php if (isset($student)): ?>
                <?php if ($student): ?>
                    <h3>Student Details</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Course</th>
                                <th>Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['firstname']); ?></td>
                                <td><?php echo htmlspecialchars($student['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($student['course']); ?></td>
                                <td><?php echo htmlspecialchars($student['year']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-records">No student found with this ID.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="list-container">
            <h2>All Students</h2>
            <?php if ($students_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Course</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($student = $students_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['firstname']); ?></td>
                                <td><?php echo htmlspecialchars($student['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($student['course']); ?></td>
                                <td><?php echo htmlspecialchars($student['year']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-records">No students found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

