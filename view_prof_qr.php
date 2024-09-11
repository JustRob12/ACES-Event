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
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f7f7f7;
            height: 100vh; /* Full viewport height */
            overflow: hidden; /* Prevent scrolling */
        }

        nav {
            width: 100%; /* Ensure navbar takes full width */
            background-color: #fc6900;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px; /* Space below navbar */
        }

        .container {
            max-width: 800px;
            width: 100%;
            height: calc(100vh - 80px); /* Adjust height to fit below the navbar */
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
            align-items: center;
        }

        #reader {
            width: 100%;
            max-width: 500px;
            border: 2px solid #fc6900;
            border-radius: 8px;
            background-color: #fff;
            margin-bottom: 20px; /* Space between scanner and result */
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
            justify-content: center; /* Center content horizontally */
            gap: 20px; /* Space between profile picture and info */
        }

        #profile-picture {
            display: none; /* Hide profile picture initially */
            border-radius: 8px;
            border: 2px solid #fc6900;
            width: 300px; /* Larger profile picture */
            height:300px; /* Larger profile picture */
            object-fit: cover; /* Ensure the image fits the box */
        }

        .info {
            flex: 1;
            text-align: left; /* Align text to the left */
        }

        .info p {
            margin: 10px 0;
            font-size: 16px;
            line-height: 1.5;
        }

        h4 {
            color: #fc6900;
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: bold;
        }

        .alert {
            color: red;
            margin-top: 10px;
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
        <h2>QR Code Scanner</h2>
        <div id="reader"></div>
        <div id="show">
            <img id="profile-picture" src="" alt="Profile Picture">
            <div class="info" id="result"></div>
        </div>
    </div>

    <script src="path/to/html5-qrcode.min.js"></script> <!-- Make sure to update the path to your local file -->
    <script src="./qrScript.js"></script> <!-- Your custom QR code scanning script -->
    <script>
        const html5Qrcode = new Html5Qrcode('reader');

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            if (decodedText) {
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
                        setTimeout(() => {
                            document.getElementById('show').style.display = 'none';
                            location.reload(); // Reload the page
                        }, 5000);

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
