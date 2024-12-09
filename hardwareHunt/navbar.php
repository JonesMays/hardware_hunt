<?php
// Start the session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- Head section remains the same -->
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
            display: flex;           /* Keep this */
            align-items: center;     /* Keep this */
            gap: 1em;               /* Keep this */
            width: 200px;           /* Keep this */
            /* Remove the display: block and text-align: right */
        }

        .header-right form {
            display: flex;
            align-items: center;
        }

        #search-bar {
            padding: 1em;
            border-radius: 15px;
            border: 1px solid #ccc;
            height: 30px;
        }

        .user-icon {
            width: 30px;
            height: auto;
            cursor: pointer;
        }
        .user-icon:hover {
            opacity: 0.7; /* Adjust opacity for hover */
        }
    </style>
</head>
<body>
<header>
    <div class="header-left">
        <a href="search.php">
            <img src="HHlogo.png" alt="Hardware Hunt Logo" class="logo">
        </a>
        <h1>Hardware Hunt</h1>
    </div>
    <nav class="header-center">
        <a href="about.php">About</a>
        <a href="component_results.php">Components</a>
        <a href="project_results.php">Projects</a>
    </nav>
    <div class="header-right">
        <?php if (isset($showPartsSearchBar) && $showPartsSearchBar): ?>
            <form action="component_results.php" method="get">
                <input type="search" id="search-bar" placeholder="Search..." name="search-parts">
            </form>
        <?php endif; ?>
        <?php if (isset($showProjectsSearchBar) && $showProjectsSearchBar): ?>
            <form action="project_results.php" method="get">
                <input type="search" id="search-bar" placeholder="Search..." name="search-projects">
            </form>
        <?php endif; ?>
        
        <!-- Modified profile icon link -->
        <a href="<?php echo isset($_SESSION['user_user_id']) ? 'profile.php' : 'login.php'; ?>">
    <img src="user-icon.png" alt="User Icon" class="user-icon">
</a>
    </div>
</header>
</body>
</html>
