<?php
// navbar.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Hunt Navbar</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c2c2e;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5em 2em;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            font-family: Arial, sans-serif;
        }

        .header-left {
            display: flex;
            align-items: center;
            color: #ffaa33;
        }

        .logo {
            width: 40px;
            height: auto;
            margin-right: 0.5em;
        }

        .header-left h1 {
            font-size: 1.5em;
            margin: 0;
        }

        .header-center {
            display: flex;
            gap: 1.5em;
        }

        .header-center a {
            color: #fff;
            text-decoration: none;
            font-size: 1em;
        }

        .header-center a:hover {
            text-decoration: underline;
        }

        .header-right {
            display: block;
            text-align: right;
            gap: 1em;
            width: 200px;
        }

        .user-icon {
            width: 30px;
            height: auto;
            cursor: pointer;
        }
    </style>
</head>
<body>
<header>
    <div class="header-left">
        <a href="hardwareHunt/search.php">
            <img src="HHlogo.png" alt="Hardware Hunt Logo" class="logo">
        </a>
        <h1>Hardware Hunt</h1>
    </div>
    <nav class="header-center">
        <a href="hardwareHunt/NewPages/about.html">About</a>
        <a href="hardwareHunt/component_results.php">Components</a>
        <a href="hardwareHunt/project_results.php">Projects</a>
    </nav>
    <div class="header-right">
        <a href="hardwareHunt/NewPages/profile.html">
            <img src="user-icon.png" alt="User Icon" class="user-icon">
        </a>
    </div>
</header>
</body>
</html>