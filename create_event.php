<?php 
// Include the database connection
require 'db_connect.php';

// Include the header and navbar
include 'header.php';
include 'navbar.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $checkin_am = $_POST['checkin_am'];
    $checkout_am = $_POST['checkout_am'];
    $checkin_pm = $_POST['checkin_pm'];
    $checkout_pm = $_POST['checkout_pm'];

    // Prepare and bind the SQL statement
    $stmt = $con->prepare("INSERT INTO tbl_events (event_name, event_date, checkin_am, checkout_am, checkin_pm, checkout_pm) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $event_name, $event_date, $checkin_am, $checkout_am, $checkin_pm, $checkout_pm);

    // Execute the statement
    if ($stmt->execute()) {
        echo '<p style="color: green;">Event registered successfully!</p>';
    } else {
        echo '<p style="color: red;">Error: ' . $stmt->error . '</p>';
    }

    // Close the statement
    $stmt->close();
}

// Delete event if delete button is clicked
if (isset($_GET['delete'])) {
    $event_id = $_GET['delete'];

    // Start transaction
    $con->begin_transaction();

    try {
        // Delete students registered for the event
        $stmt = $con->prepare("DELETE FROM tbl_event_attendance WHERE event_id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $stmt->close();

        // Delete the event
        $stmt = $con->prepare("DELETE FROM tbl_events WHERE id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $con->commit();
        $deleted = true; // Flag to indicate deletion
    } catch (Exception $e) {
        // Rollback transaction on error
        $con->rollback();
        echo '<p style="color: red;">Error: ' . $e->getMessage() . '</p>';
    }
}

// Fetch all events
$sql = "SELECT * FROM tbl_events";
$events = $con->query($sql);

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .navbar {
            background-color: #FFA500; /* Navbar background color */
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        .navbar ul li {
            margin: 0 15px;
        }
        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar ul li a:hover {
            text-decoration: underline;
        }
        .container {
            display: flex;
            justify-content: space-around;
            padding: 0px;
        }
        .form-container, .events-container {
            width: 48%;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
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
        .form-group input[type="time"] {
            width: 48%;
            display: inline-block;
        }
        .form-group input[type="submit"] {
            width: 100%;
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
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Register Event</h2>
            <form method="post" action="create_event.php">
                <div class="form-group">
                    <label for="event_name">Event Name:</label>
                    <input type="text" id="event_name" name="event_name" required>
                </div>
                <div class="form-group">
                    <label for="event_date">Event Date:</label>
                    <input type="date" id="event_date" name="event_date" required>
                </div>
                <div class="form-group">
                    <label>Check-in AM / Check-out AM:</label>
                    <input type="time" id="checkin_am" name="checkin_am" required>
                    <input type="time" id="checkout_am" name="checkout_am" required>
                </div>
                <div class="form-group">
                    <label>Check-in PM / Check-out PM:</label>
                    <input type="time" id="checkin_pm" name="checkin_pm" required>
                    <input type="time" id="checkout_pm" name="checkout_pm" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Register Event">
                </div>
            </form>
        </div>
        <div class="events-container">
            <h2>Event List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <!-- <th>Check-in AM</th>
                        <th>Check-out AM</th>
                        <th>Check-in PM</th>
                        <th>Check-out PM</th> -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($event = $events->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                            <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                            <!-- <td><?php echo htmlspecialchars($event['checkin_am']); ?></td>
                            <td><?php echo htmlspecialchars($event['checkout_am']); ?></td>
                            <td><?php echo htmlspecialchars($event['checkin_pm']); ?></td>
                            <td><?php echo htmlspecialchars($event['checkout_pm']); ?></td> -->
                            <td>
                                <a href="create_event.php?delete=<?php echo $event['id']; ?>" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this event?')) { document.getElementById('delete-form-<?php echo $event['id']; ?>').submit(); }">Delete</a>
                                <form id="delete-form-<?php echo $event['id']; ?>" method="get" action="create_event.php" style="display:none;">
                                    <input type="hidden" name="delete" value="<?php echo $event['id']; ?>">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        // Refresh the page if a delete operation was performed
        <?php if (isset($deleted) && $deleted) { ?>
            window.location.reload();
           
        
        <?php } 
        $stmt->close(); ?>
    </script>
</body>
</html>
