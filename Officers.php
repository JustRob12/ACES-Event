<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACES Photo Gallery</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>
        
        body {
            width: 100%;
            margin: 0;
            padding: 0;
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            background-color: white;
            font-family: 'Poppins', sans-serif;
            /* border: 10px solid; */
            /* border-image: linear-gradient(45deg, #fc6900, #ff8c00) 1; */
        }

        .gallery {
            display: flex;
            flex-direction: column;
            margin-top: 80px;
            padding: 20px;
        }

        .gallery-row {
            display: flex;
            flex-wrap: wrap; /* Allows items to wrap to new rows */
            justify-content: center;
            margin-bottom: 20px;
        }

        .gallery-item {
            width: 250px;
            margin: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
            transition: transform 0.3s ease-in-out; /* Smooth zoom effect */
        }

        .gallery-item:hover {
            transform: scale(1.1); /* Zoom in effect on hover */
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.3s ease-in-out; /* Smooth zoom effect for the image */
        }

        .gallery-item:hover img {
            transform: scale(1.1); /* Zoom in effect for the image */
        }


        .gallery-item .description {
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #333;
        }

        .gallery-item .description .name {
            font-weight: bold;
        }

        .gallery-item .description .position {
            font-weight: 500; /* Light bold */
            font-style: italic;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .gallery-item {
                width: 45%; /* Adjust item width on smaller screens */
            }
        }

        @media (max-width: 480px) {
            .gallery-item {
                width: 90%; /* Full width on extra small screens */
                margin: 5px; /* Smaller margins */
            }

            .gallery-row {
                flex-direction: column; /* Stack images vertically */
                align-items: center; /* Center images */
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="gallery">
        <!-- Top Row (2 Pictures) -->
        <div class="gallery-row">
            <div class="gallery-item">
                <img src="picture1.jpg" alt="Description 1">
                <div class="description">
                    <span class="name">April Joy B. Uy</span> <br>
                    <span class="position">ADVISER</span>
                </div>
            </div>
            <div class="gallery-item">
                <img src="picture2.jpg" alt="Description 2">
                <div class="description">
                    <span class="name">Clint Laurence Mabandos Dueñas</span> <br>
                    <span class="position">CO-ADVISER</span>
                </div>
            </div>
        </div>

        <!-- Middle Row (4 Pictures) -->
        <div class="gallery-row">
            <div class="gallery-item">
                <img src="picture3.png" alt="Description 3">
                <div class="description">
                    <span class="name">Tristan C. Portugaliza</span> <br>
                    <span class="position">GOVERNOR</span>
                </div>
            </div>
            <div class="gallery-item">
                <img src="picture4.png" alt="Description 4">
                <div class="description">
                    <span class="name">John Paul C. Colita</span> <br>
                    <span class="position">VICE-GOVERNOR</span>
                </div>
            </div>
            <div class="gallery-item">
                <img src="picture9.png" alt="Description 5">
                <div class="description">
                    <span class="name">Zandra Kate L. Ruin</span> <br>
                    <span class="position">SECRETARY</span>
                </div>
            </div>
        </div>

        <!-- Bottom Row (4 Pictures) -->
        <div class="gallery-row">
            <div class="gallery-item">
                <img src="picture5.png" alt="Description 7">
                <div class="description">
                    <span class="name">Kate L. Rodriguez</span> <br>
                    <span class="position">TREASURER</span>
                </div>
            </div>
            <div class="gallery-item">
                <img src="picture6.png" alt="Description 8">
                <div class="description">
                    <span class="name">Rodgeline L. Bansagan</span> <br>
                    <span class="position">AUDITOR</span>
                </div>
            </div>
            <div class="gallery-item">
                <img src="picture8.png" alt="Description 8">
                <div class="description">
                    <span class="name">Rezrafel A. Umbukan</span> <br>
                    <span class="position">Business Manager</span>
                </div>
            </div>
            <div class="gallery-item">
                <img src="picture7.png" alt="Description 9">
                <div class="description">
                    <span class="name">Roberto M. Prisoris Jr.</span> <br>
                    <span class="position">Public Information Officer</span>
                </div>
                
            </div>
            <!-- <div class="gallery-item">
                <img src="picture12.png" alt="Description 9">
                <div class="description">
                    <span class="name">Lady Marianne O. Bauyot</span> <br>
                    <span class="position">Muse</span>
                </div>
                
            </div> -->
        </div>
    </div>
</body>
</html>
