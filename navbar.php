<!-- navbar.php -->
<nav class="center-nav">
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="ACES.php">Announcement</a></li>
        <li class="students">
            <a href="#" onclick="toggleSecondaryBar(event)">Students</a>
            <ul class="secondary-bar">
                <li><a href="Student.php">Register Students</a></li>
                <li><a href="ShowDisplayProfile.php">View Students</a></li>
                <li><a href="view_prof_qr.php">QR View Students</a></li>
            </ul>
        </li>
        <li class="activities">
            <a href="#" onclick="toggleSecondaryBar(event)">Activities</a>
            <ul class="secondary-bar">
                <li><a href="calendar.php">Calendar</a></li>
                <li><a href="create_event.php">Create Event</a></li>
                <li><a href="attendance.php">Attendance</a></li>
                <li><a href="attendance_report.php">Attendance Report</a></li>
            </ul>
        </li>
        <li><a href="Officers.php">Officers</a></li>
    </ul>
</nav>

<style>
    /* Center Navbar Styles */
    .center-nav {
        background-color: #fc6900;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
    }

    .center-nav ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .center-nav li {
        margin: 0 15px;
        position: relative; /* For positioning the secondary bar */
    }

    .center-nav a {
        text-decoration: none;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        font-weight: 700;
        padding: 10px;
    }

    .center-nav a:hover {
        text-decoration: underline;
    }

    /* Secondary Bar Styles */
    .secondary-bar {
        display: none; /* Ensure the dropdown is hidden initially */
        position: absolute;
        top: 100%; /* Position below the parent link */
        left: 0;
        background-color: #fc6900;
        list-style-type: none;
        padding: 10px 0;
        margin: 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        min-width: 150px; /* Minimum width for dropdown */
    }

    .secondary-bar li {
        margin: 0;
    }

    .secondary-bar a {
        font-size: 14px;
        font-weight: 600;
        padding: 8px 20px;
        display: block;
    }

    .secondary-bar a:hover {
        background-color: #e05a00;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .center-nav ul {
            flex-direction: column;
            align-items: center;
        }

        .center-nav li {
            margin: 10px 0;
        }

        .secondary-bar {
            position: static; /* Reset position for responsive */
            box-shadow: none;
        }
    }
</style>

<script>
    // JavaScript to toggle the secondary bar on click
    function toggleSecondaryBar(event) {
        event.preventDefault(); // Prevent the default anchor behavior
        const secondaryBar = event.target.nextElementSibling;
        // Check if the clicked element has a sibling that is the secondary bar
        if (secondaryBar && secondaryBar.classList.contains('secondary-bar')) {
            // Toggle display of the secondary bar
            const isVisible = secondaryBar.style.display === 'block';
            // Hide all secondary bars first (if needed)
            document.querySelectorAll('.secondary-bar').forEach(bar => bar.style.display = 'none');
            // Show or hide the clicked secondary bar
            secondaryBar.style.display = isVisible ? 'none' : 'block';
        }
    }

    // Ensure no secondary bar is visible initially
    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.secondary-bar').forEach(bar => bar.style.display = 'none');
    });
</script>
