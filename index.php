<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - ACES</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        header {
            width: 100%;
            background-color: #fc6900;
            color: #fff;
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
            font-size: 30px;
            margin: 0;
            font-weight: 700;
        }

        .subtitle {
            font-size: 12px;
            margin: 0;
            font-weight: 400;
        }

        .content {
            flex: 1;
            padding: 20px;
            text-align: center;
        }

        .content h1 {
            font-size: 32px;
            margin: 0;
            color: #333;
        }

        .content p {
            margin: 15px 0;
            font-size: 18px;
            color: #666;
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

            .content h1 {
                font-size: 24px; /* Smaller heading font */
            }

            .content p {
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

            .content h1 {
                font-size: 20px; /* Smaller heading font */
            }

            .content p {
                font-size: 14px; /* Smaller paragraph font */
            }
        }
    </style>
</head>
<body>
    
    <?php include 'header.php'; ?>

    <?php include 'navbar.php'; ?>

    <div class="content">
        <h1>Welcome to ACES</h1>
        <p>The Association of Computing and Engineering Students (ACES) is dedicated to fostering the growth and development of students in the fields of computing and engineering. Explore our site to learn more about our activities, members, and resources.</p>
    </div>
</body>
</html>
