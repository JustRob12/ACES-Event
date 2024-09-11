<?php 
// Include the database connection
require 'db_connect.php';

// Include the header and navbar
include 'header.php';
include 'navbar.php';

// Get the current month and year or set defaults
$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Calculate the first and last days of the month
$firstDayOfMonth = new DateTime("$currentYear-$currentMonth-01");
$lastDayOfMonth = new DateTime($firstDayOfMonth->format('Y-m-t'));

// Get events for the current month
$sql = "SELECT * FROM tbl_events WHERE MONTH(event_date) = ? AND YEAR(event_date) = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $currentMonth, $currentYear);
$stmt->execute();
$events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$con->close();

// Helper function to get events for a specific date
function getEventsForDate($date, $events) {
    $formattedDate = $date->format('Y-m-d');
    $eventsForDate = array_filter($events, function($event) use ($formattedDate) {
        return $event['event_date'] === $formattedDate;
    });
    return $eventsForDate;
}

// Helper function to create a link for navigation
function createNavLink($month, $year, $text) {
    return "<a href=\"calendar.php?month=$month&year=$year\" class=\"nav-link\">$text</a>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Calendar</title>
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
            max-width: 1200px; /* Increase width for larger calendar */
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #ccc;
            font-size: 16px; /* Larger font size for better visibility */
        }
        .calendar div {
            padding: 15px; /* Increased padding for better spacing */
            text-align: center;
            background: #fff;
            border: 1px solid #ddd;
            cursor: pointer;
        }
        .calendar .day-header {
            background: #FFA500;
            color: white;
            font-weight: bold;
            font-size: 18px; /* Larger font size for day headers */
        }
        .calendar .day {
            position: relative;
            cursor: pointer;
        }
        .calendar .day.has-event {
            background: #CC5500; /* Light blue background for days with events */
        }
        .calendar .empty {
            background: #f4f4f4;
        }
        .nav-controls {
            text-align: center;
            margin-bottom: 20px;
        }
        .nav-controls a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
        }
        .nav-controls a:hover {
            text-decoration: underline;
        }
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            max-width: 800px;
        }
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
        .event-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .event-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            align-items: center;
        }
        .event-item p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-controls">
            <?php 
            $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
            $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
            $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
            $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;

            echo createNavLink($prevMonth, $prevYear, '&laquo; Previous');
            echo '<span>' . $firstDayOfMonth->format('F Y') . '</span>';
            echo createNavLink($nextMonth, $nextYear, 'Next &raquo;');
            ?>
        </div>

        <div class="calendar">
            <?php
            // Print header for days of the week
            $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            foreach ($daysOfWeek as $day) {
                echo "<div class=\"day-header\">$day</div>";
            }

            // Print empty cells before the first day of the month
            $firstDayOfWeek = $firstDayOfMonth->format('w');
            for ($i = 0; $i < $firstDayOfWeek; $i++) {
                echo '<div class="empty"></div>';
            }

            // Print days of the month
            $currentDate = $firstDayOfMonth;
            while ($currentDate <= $lastDayOfMonth) {
                $eventsForDate = getEventsForDate($currentDate, $events);
                $hasEventClass = !empty($eventsForDate) ? 'has-event' : '';
                echo '<div class="day ' . $hasEventClass . '" onclick="openModal(\'' . $currentDate->format('Y-m-d') . '\')">';
                echo $currentDate->format('j');
                echo '</div>';
                $currentDate->modify('+1 day');
            }
            ?>
        </div>
    </div>

    <!-- Modal for displaying events -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalDate"></h2>
            <div id="modalEvents" class="event-list"></div>
        </div>
    </div>

    <script>
        // JavaScript to handle modal
        function openModal(date) {
            document.getElementById('modalDate').innerText = date;
            fetch('fetch_events.php?date=' + encodeURIComponent(date))
                .then(response => response.json())
                .then(events => {
                    let eventsHtml = '';
                    events.forEach(event => {
                        eventsHtml += '<div class="event-item"><p><strong>' + event.event_name + '</strong></p><p>'  + '</p></div>';
                    });
                    document.getElementById('modalEvents').innerHTML = eventsHtml;
                })
                .catch(error => console.error('Error fetching events:', error));

            document.getElementById('eventModal').style.display = 'block';
        }

        // Close the modal
        document.querySelector('.close').onclick = function() {
            document.getElementById('eventModal').style.display = 'none';
        }

// Close the modal when clicking outside of it
window.onclick = function(event) {
    if (event.target == document.getElementById('eventModal')) {
        document.getElementById('eventModal').style.display = 'none';
    }
}
</script>
</body>
</html>
