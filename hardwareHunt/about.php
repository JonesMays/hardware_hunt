<?php
    $showSearchBar = false;
    include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #1c1c1e;
            color: #ffffff;
        }

        .content {
            max-width: 1200px;
            margin: 2em auto;
            padding: 1em;
            text-align: center;
        }

        .content h1 {
            color: #ffaa33;
            margin-bottom: 2em;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2em;
            justify-content: center;
        }

        .profile-box {
            background-color: #2c2c2e;
            padding: 2em 1em;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .profile-box img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 1em;
        }

        .profile-box h3 {
            font-size: 1.2em;
            color: #ffaa33;
            margin: 0.5em 0;
        }

        .profile-box p {
            font-size: 1em;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5em;
            }
        }

        @media (max-width: 480px) {
            .grid-container {
                grid-template-columns: 1fr;
                gap: 1em;
            }
        }
    </style>
</head>
<body>
    <!--
    <iframe src="navbar.html" style="border: none; width: 100%; height: auto;" id="navbar"></iframe>
    -->


    <main class="content">
        <h1>Meet Our Team!</h1>
        <div class="grid-container">
            <div class="profile-box">
                <img src="profileImages/Amanda%20Promo%20serious%20(edited).jpg" alt="Person 1">
                <h3>Amanda Nepo</h3>
                <p>CEO | Developer</p>
            </div>
            <div class="profile-box">
                <img src="profileImages/charlotte.png" alt="Person 2">
                <h3>Charlotte Chang</h3>
                <p>Developer | Data Analyst</p>
            </div>
            <div class="profile-box">
                <img src="profileImages/eshaan.png" alt="Person 3">
                <h3>Eshaan Kothari</h3>
                <p>PM | Developer | Data Analyst</p>
            </div>
            <div class="profile-box">
                <img src="profileImages/jones.png" alt="Person 4">
                <h3>Jones Mays</h3>
                <p>Developer | AI Analyst</p>
            </div>
            <div class="profile-box">
                <img src="profileImages/skye.png" alt="Person 5">
                <h3>Skye Gartner</h3>
                <p>Developer | Data Analyst</p>
            </div>
        </div>
    </main>
    
</body>
</html>
