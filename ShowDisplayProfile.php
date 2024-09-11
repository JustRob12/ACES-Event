<?php
// Include database connection, header, and navbar
require 'db_connect.php';
include 'header.php'; // Assumes header.php includes the header and necessary styles/scripts
include 'navbar.php'; // Assumes navbar.php contains the navbar with orange background

// Set the default course filter and search filter
$course_filter = isset($_GET['course']) ? $_GET['course'] : '';
$search_filter = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch all students from the database based on course and search filters
$query = "SELECT student_id, firstname, lastname FROM tbl_student WHERE 1=1";
$params = [];

// Filter by course if specified
if ($course_filter) {
    $query .= " AND course = ?";
    $params[] = $course_filter;
}

// Filter by search if specified (searching both ID and name)
if ($search_filter) {
    $query .= " AND (student_id LIKE ? OR CONCAT(firstname, ' ', lastname) LIKE ?)";
    $params[] = "%$search_filter%";
    $params[] = "%$search_filter%";
}

$stmt = $con->prepare($query);

// Bind parameters
if ($params) {
    $types = str_repeat('s', count($params)); // All parameters are strings
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Display Student Profiles</title>
    <script src="path_to/qrcode.min.js"></script> <!-- Include the QR code library -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
        }

        .container {
            display: flex;
            gap: 20px;
        }

        .student-list {
            width: 30%;
            border-right: 1px solid #ddd;
            padding-right: 20px;
            overflow-y: auto;
            max-height: 80vh;
        }

        .search-bar,
        .course-filter {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: calc(100% - 22px);
            font-size: 14px;
        }

        .course-filter {
            margin-bottom: 20px;
        }

        .student-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .student-list li {
            margin: 10px 0;
        }

        .student-list a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            display: block;
            padding: 8px 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .student-list a:hover {
            background-color: #fc6900;
            color: white;
        }

        .student-details {
            width: 70%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 20px;
        }

        .student-details img {
            width: 350px;
            height: 350px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .student-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #555;
            flex-grow: 1;
        }

        .student-info p {
            margin: 5px 0;
            font-size: 20px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        .action-buttons {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .edit-button {
            background-color: #4CAF50;
        }

        .edit-button:hover {
            background-color: #45a049;
        }

        .delete-button {
            background-color: #f44336;
        }

        .delete-button:hover {
            background-color: #e41e1e;
        }

      /* Modal Background */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

/* Buttons for modal footer */
.modal-footer {
    display: flex;
    justify-content: space-between;
}

.confirm-button, .cancel-button {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.confirm-button {
    background-color: #fc6900;
    color: white;
}

.confirm-button:hover {
    background-color: #e05a00;
}

.cancel-button {
    background-color: #ccc;
    color: black;
}

.cancel-button:hover {
    background-color: #999;
}

/* Form Elements */
form label {
    display: block;
    margin: 10px 0 5px;
}

form input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

        .save-button {
            background-color: #4CAF50;
        }

        .save-button:hover {
            background-color: #45a049;
        }

        .confirm-button {
            background-color: #f44336;
        }

        .confirm-button:hover {
            background-color: #e41e1e;
        }

        .cancel-button {
            background-color: #777;
        }

        .cancel-button:hover {
            background-color: #555;
        }

        /* Search Button Styles */
        form#searchForm {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form#searchForm button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #fc6900;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 10px;
        }

        form#searchForm button:hover {
            background-color: #e55b00;
            transform: scale(1.05);
        }

        form#searchForm button:focus {
            outline: none;
        }

        .qr-code-button {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    background-color: #fc6900;
    color: white;
    font-weight: bold;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s, transform 0.3s;
    cursor: pointer;
}

.qr-code-button:hover {
    background-color: #e55b00;
    transform: scale(1.05);
}

.qr-code-button:focus {
    outline: none;
}

    </style>
</head>
<body>
<div class="container">
        <div class="student-list">
            <h2>Registered Students</h2>
            <form id="searchForm">
                <input type="text" name="search" id="search" class="search-bar" placeholder="Search by ID or name..." value="<?= htmlspecialchars($search_filter) ?>">
                <select id="courseFilter" name="course" class="course-filter">
                    <option value="">All Courses</option>
                    <option value="BSIT" <?= $course_filter === 'BSIT' ? 'selected' : '' ?>>BSIT</option>
                    <option value="BSCE" <?= $course_filter === 'BSCE' ? 'selected' : '' ?>>BSCE</option>
                    <option value="BSM" <?= $course_filter === 'BSM' ? 'selected' : '' ?>>BSM</option>
                    <option value="BITM" <?= $course_filter === 'BITM' ? 'selected' : '' ?>>BITM</option>
                </select>
                <button type="submit">Search</button>
            </form>
            <ul id="studentList">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li><a href="?student_id=<?= $row['student_id'] ?>&course=<?= urlencode($course_filter) ?>&search=<?= urlencode($search_filter) ?>"><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></a></li>
                <?php endwhile; ?>
            </ul>
        </div>

        <div class="student-details">
            <?php
            // Check if a student is selected
            if (isset($_GET['student_id'])) {
                $student_id = $_GET['student_id'];
                // Fetch the selected student's information from the database
                $stmt = $con->prepare("SELECT * FROM tbl_student WHERE student_id = ?");
                $stmt->bind_param("s", $student_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $student = $result->fetch_assoc();

                if ($student) {
                    echo '<img src="data:image/png;base64,' . base64_encode($student['profile']) . '" alt="Profile Picture">';
                    echo '<div class="student-info">';
                    echo '<p><strong>Student ID:</strong> ' . htmlspecialchars($student['student_id']) . '</p>';
                    echo '<p><strong>First Name:</strong> ' . htmlspecialchars($student['firstname']) . '</p>';
                    echo '<p><strong>Middle Name:</strong> ' . htmlspecialchars($student['middlename']) . '</p>';
                    echo '<p><strong>Last Name:</strong> ' . htmlspecialchars($student['lastname']) . '</p>';
                    echo '<p><strong>Course:</strong> ' . htmlspecialchars($student['course']) . '</p>';
                    echo '<p><strong>Year:</strong> ' . htmlspecialchars($student['year']) . '</p>';
                    echo '<p><strong>Email:</strong> ' . htmlspecialchars($student['gmail']) . '</p>';
                    // Display action buttons
                    echo '<div class="action-buttons">';
                    echo '<button class="edit-button" onclick="openEditModal()">Edit</button>';
                    echo '<button class="delete-button" onclick="openDeleteModal()">Delete</button>';
                    echo '<a href="generate_qr_code.php?student_id=' . urlencode($student['student_id']) . '" class="qr-code-button" " target="_blank" >Generate QR Code</a>';
                    echo '</div>';
                    echo '</div>';

                    // QR Code Generation Section
                    echo '<div id="qrcode"></div>';
                    echo '<script type="text/javascript">
                        document.addEventListener("DOMContentLoaded", function() {
                            new QRCode(document.getElementById("qrcode"), {
                                text: ' . json_encode(json_encode([
                                    'student_id' => $student['student_id'],
                                    'firstname' => $student['firstname'],
                                    'middlename' => $student['middlename'],
                                    'lastname' => $student['lastname'],
                                    'course' => $student['course'],
                                    'year' => $student['year'],
                                    'email' => $student['gmail']
                                ])) . ',
                                width: 128,
                                height: 128
                            });
                        });
                    </script>';
                } else {
                    echo '<p class="error">Student not found.</p>';
                }
            } else {
                echo '<p class="error">No student selected.</p>';
            }
            ?>
        </div>
    </div>
    <!-- Edit Modal -->
    
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Student Details</h2>
        <form id="editForm" method="post" action="update_student.php" enctype="multipart/form-data">
            <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($student['firstname']) ?>" required>
            <label for="middlename">Middle Name:</label>
            <input type="text" id="middlename" name="middlename" value="<?= htmlspecialchars($student['middlename']) ?>" required>
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($student['lastname']) ?>" required>
            <label for="course">Course:</label>
            <input type="text" id="course" name="course" value="<?= htmlspecialchars($student['course']) ?>" required>
            <label for="year">Year:</label>
            <input type="text" id="year" name="year" value="<?= htmlspecialchars($student['year']) ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['gmail']) ?>" required>
            
            <?php if (!empty($profile)): ?>
                <img src="data:image/png;base64,<?= $profile ?>" alt="Current Profile Picture" width="150">
            <?php endif; ?>

            <!-- Profile Picture Upload Field -->
            <label for="profile">Profile Picture:</label>
            <input type="file" id="profile" name="profile">
                    
            <div class="modal-footer">
                <button type="submit" class="save-button">Save Changes</button>
                <button type="button" class="cancel-button" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>



    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDeleteModal()">&times;</span>
            <h2>Delete Student</h2>
            <p>Are you sure you want to delete this student?</p>
            <div class="modal-footer">
                <form id="deleteForm" method="post" action="delete_student.php">
                    <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>">
                    <button type="submit" class="confirm-button">Yes, Delete</button>
                </form>
                <button class="cancel-button" onclick="closeDeleteModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
       // Function to open the modal
function openEditModal() {
    document.getElementById('editModal').style.display = 'block';
}

// Function to close the modal
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close the modal if the user clicks anywhere outside of it
window.onclick = function(event) {
    if (event.target == document.getElementById('editModal')) {
        closeEditModal();
    }
}

        // JavaScript to filter student names based on search input
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form submission
            let search = document.getElementById('search').value;
            let courseFilter = document.getElementById('courseFilter').value;
            window.location.href = `?search=${encodeURIComponent(search)}&course=${encodeURIComponent(courseFilter)}`;
        });

        // JavaScript to filter students based on selected course
        document.getElementById('courseFilter').addEventListener('change', function() {
            document.getElementById('searchForm').dispatchEvent(new Event('submit'));
        });

        function openDeleteModal() {
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close the modal when clicking outside of the modal-content
window.onclick = function(event) {
    var modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
    </script>
</body>
</html>

<?php
$con->close();
?>
