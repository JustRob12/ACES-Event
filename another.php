<?php
// another.php
// Fetch the student ID from the URL
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : null;

// Fetch student information from the database (replace with your actual DB query)
$student_info = [];
if ($student_id) {
    // Assume you have a function fetchStudentInfo($student_id) to get student data from the database
    // Example function:
    // $student_info = fetchStudentInfo($student_id);
    // Example fetched data (replace this with real database fetching):
    $student_info = [
        'firstname' => 'John',
        'lastname' => 'Doe',
        'course' => 'Computer Science',
        'year' => '3rd Year',
        'profile' => 'path/to/profile.jpg' // Assuming you have a profile image path
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Information</title>
    <!-- Include Bootstrap for easy modal styling (optional) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Additional styles for centering the modal */
        .modal-dialog {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Full screen height */
        }
    </style>
</head>
<body>

<!-- Modal Structure -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">Student Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if ($student_info): ?>
                    <p><strong>First Name:</strong> <?= htmlspecialchars($student_info['firstname']) ?></p>
                    <p><strong>Last Name:</strong> <?= htmlspecialchars($student_info['lastname']) ?></p>
                    <p><strong>Course:</strong> <?= htmlspecialchars($student_info['course']) ?></p>
                    <p><strong>Year:</strong> <?= htmlspecialchars($student_info['year']) ?></p>
                    <img src="<?= htmlspecialchars($student_info['profile']) ?>" alt="Profile Picture" style="width: 100px; height: auto;">
                <?php else: ?>
                    <p>Student information not found.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap and jQuery for modal functionality -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Show the modal automatically when the page loads
    $(document).ready(function() {
        $('#infoModal').modal('show');
    });
</script>

</body>
</html>
