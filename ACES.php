<?php
// Your PHP code (if any) goes here.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACES</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>
        * {
            box-sizing: border-box;
        }
        h1, h2, p, .title, .subtitle, .code {
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
        }
        h1 {
            text-align: center;
            font-weight: 700; /* Bold for h1 */
        }
        h2 {
            text-align: center;
            font-weight: 400; /* Regular weight for h2 */
        }

        body {
            width: 100%;
            margin: 0;
            padding: 0;
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            background-color: white;
        }

        header {
            width: 100%;
            background-color: #fc6900;
            color: rgb(255, 255, 255);
            display: flex;
            align-items: center;
            padding: 10px 20px;
            position: relative;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 55px;
            height: auto;
            margin-right: 15px;
        }

        .header-text {
            display: flex;
            flex-direction: column;
        }

        .title {
            font-size: 35px;
            margin: 0;
            font-weight: 700; /* Bold for header title */
        }

        .subtitle {
            font-size: 12px;
            margin: 0;
            font-weight: 400; /* Regular for subtitle */
        }

        iframe {
            margin: auto;
            margin-top: 10px;
            width: 90%;
            height: 800px;
            max-height: 80vh;
        }

        hr {
            color: black;
            width: 100%;
            margin-top: 20px;
        }

        p {
            width: 90%;
            margin: auto;
        }

        .instruction {
            width: 90%;
            margin: auto;
            text-align: center;
            background-color: white;
        }

        .code {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            font-style: italic;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .logo {
                width: 40px; /* Smaller logo */
            }

            .title {
                font-size: 24px; /* Smaller title font */
            }

            .subtitle {
                font-size: 10px; /* Smaller subtitle font */
            }

            h1 {
                font-size: 24px; /* Smaller heading font */
            }

            h2 {
                font-size: 18px; /* Smaller subheading font */
            }

            p {
                font-size: 16px; /* Smaller paragraph font */
            }
        }

        @media (max-width: 480px) {
            header {
                padding: 10px;
                flex-direction: column; /* Stack header items */
                align-items: flex-start;
            }

            .logo {
                width: 35px; /* Even smaller logo */
                margin-bottom: 10px; /* Space between logo and text */
            }

            .title {
                font-size: 20px; /* Smaller title font */
            }

            .subtitle {
                font-size: 8px; /* Smaller subtitle font */
            }

            h1 {
                font-size: 20px; /* Smaller heading font */
            }

            h2 {
                font-size: 16px; /* Smaller subheading font */
            }

            p {
                font-size: 14px; /* Smaller paragraph font */
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <?php include 'navbar.php'; ?>

    <div class="instruction">
        <h1>QR CODE Generator</h1>
        <br>
        <h2>To generate a QR code with the ACES QR Code Generator, please input the required data in the following format:</h2>
        <br>
        <p class="code">id number, full name, course, year</p>
    </div>

    <div class="instruction">
        <p><strong>Example:</strong></p>
        <p class="code">1234-1234 , Roberto M. Prisoris Jr. , BSIT , 3</p>
        <br>
        <p class="code">Don't forget the Comma(,)</p>
    </div>

    <hr>
    <h2><a>Type your Information here</a></h2>

    <iframe src="tiny-qr.html"></iframe>
</body>
</html>
