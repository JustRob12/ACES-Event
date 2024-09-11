<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        nav {
            width: 100%;
            background-color: #fc6900;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .container {
            max-width: 1200px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        #scanner-container {
            width: 50%;
            padding: 20px;
            box-sizing: border-box;
        }

        #reader {
            width: 100%;
            border: 2px solid #fc6900;
            border-radius: 8px;
            background: #fff;
        }

        #show {
            display: none;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            width: 100%;
            margin-top: 20px;
            position: relative;
        }

        #result {
            color: #333;
        }

        #result img {
            display: block;
            margin: 0 auto 20px;
            border-radius: 50%;
            border: 2px solid #fc6900;
            width: 150px;
            height: auto;
        }

        #result p {
            margin: 10px 0;
        }

        .record-container {
            width: 50%;
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto;
        }

        h4 {
            color: #fc6900;
            margin-bottom: 20px;
        }

        .record-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .record-table th, .record-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .record-table th {
            background-color: #fc6900;
            color: white;
        }

        .record-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .export-button {
            padding: 10px 20px;
            background-color: #fc6900;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px 0;
        }

        .export-button:hover {
            background-color: #e65a00;
        }
    </style>
</head>
<body>
    <?php
    // Include database connection, header, and navbar
    include 'header.php'; // Assumes header.php includes the header and necessary styles/scripts
    include 'navbar.php'; // Assumes navbar.php contains the navbar with orange background
    ?>

    <div class="container">
        <div id="scanner-container">
            <h2>QR Code Scanner</h2>
            <div id="reader"></div>
            <div id="show">
                <h4>Scanned Result</h4>
                <p id="result"></p>
            </div>
        </div>

        <div class="record-container">
            <h2>Scanned Records</h2>
            <table id="records" class="record-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Scanned records will be dynamically inserted here -->
                </tbody>
            </table>
            <button id="export" class="export-button">Export as CSV</button>
        </div>
    </div>

    <script src="path/to/html5-qrcode.min.js"></script> <!-- Update path to your local file -->
    <script src="./qrScript.js"></script> <!-- Your custom QR code scanning script -->
    <script>
        let html5Qrcode;

        // Function to initialize the QR code scanner
        function initializeScanner() {
            html5Qrcode = new Html5Qrcode('reader');
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            html5Qrcode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
                .catch(error => {
                    console.error('Error starting the scanner:', error);
                    alert('An error occurred while starting the QR code scanner.');
                });
        }

        // Function to stop the QR code scanner
        function stopScanner() {
            html5Qrcode.stop()
                .catch(error => {
                    console.error('Error stopping the scanner:', error);
                });
        }

        // Callback function for successfully decoded QR code
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            if (decodedText) {
                stopScanner(); // Stop scanning after successful decode

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
                        // Display scanned result
                        document.getElementById('result').innerHTML = `
                            <img src="${data.profile}" alt="Profile Picture">
                            <p><strong>Name:</strong> ${data.firstname} ${data.middlename} ${data.lastname}</p>
                            <p><strong>Course:</strong> ${data.course}</p>
                            <p><strong>Year:</strong> ${data.year}</p>
                        `;
                        document.getElementById('show').style.display = 'block';

                        // Add scanned record to table
                        const now = new Date();
                        const time = now.toLocaleTimeString();
                        const row = `<tr>
                            <td>${data.firstname} ${data.middlename} ${data.lastname}</td>
                            <td>${data.course}</td>
                            <td>${data.year}</td>
                            <td>${time}</td>
                        </tr>`;
                        document.querySelector('#records tbody').insertAdjacentHTML('beforeend', row);

                        // Hide the information and restart scanner after 5 seconds
                        setTimeout(() => {
                            document.getElementById('show').style.display = 'none';
                            initializeScanner(); // Restart scanner after a short delay
                        }, 5000);
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

        // Initialize the QR code scanner when the page loads
        window.onload = initializeScanner;

        // Export table to CSV
        document.getElementById('export').addEventListener('click', () => {
            const rows = Array.from(document.querySelectorAll('.record-table tr'));
            const csvContent = rows.map(row => Array.from(row.querySelectorAll('th, td'))
                .map(cell => `"${cell.textContent.replace(/"/g, '""')}"`)
                .join(','))
                .join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'scanned_records.csv';
            a.click();
            URL.revokeObjectURL(url);
        });
    </script>
</body>
</html>
