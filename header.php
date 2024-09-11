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
    </style>
</head>
<body>
    <header>
        <img src="ACES LOGO.png" alt="Logo" class="logo"> <!-- Add your logo here -->
        <div class="header-text">
            <div class="title">ACES</div>
            <div class="subtitle">Association of Computing and Engineering Students</div>
        </div>
    </header>
