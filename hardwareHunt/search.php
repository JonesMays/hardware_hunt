<?php

// var_dump output variables ot the screen
// request contains all submitted form information
// var_dump($_REQUEST);

// echo "<br><br>hello " . $_REQUEST["fullname"];

//step 1 connect to database

$host = "webdev.iyaserver.com";
$userid = "nepo";
$userpw = "BackendMagic1024";
$db = "nepo_hardwareHunt2";

// inlcude "../anvariables.php";

$mysql = new mysqli(
    $host,
    $userid,
    $userpw,
    $db
);

// if I can find an error number then stop because there was a problem
if($mysql->connect_errno) { //if error
    echo "db connection error : " . $mysql->connect_error; //tell me there was an erro
    exit(); //stop running page
} else {
    // echo "db connection success!"; //slaytastic. no errors, removing to get rid of it on page
    //if you mess up username password serve then this error will come up.
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Hunt - Home</title>
    <link rel="stylesheet" href="menu.css">
    <style>
        /** {*/
        /*    box-sizing: border-box;*/
        /*    margin: 0;*/
        /*    padding: 0;*/
        /*}*/

        body {
            /*    font-family: Arial, sans-serif;*/
            /*    display: flex;*/
            /*    flex-direction: column;*/
            /*    min-height: 100vh;*/
            /*    color: #ffffff;*/
            background-image: url("background.png");
            background-repeat: no-repeat;
            background-size: 100vw 100vh;
        }
        /*header {*/
        /*    background-color: #2c2c2e;*/
        /*    color: #fff;*/
        /*    display: flex;*/
        /*    justify-content: space-between;*/
        /*    align-items: center;*/
        /*    padding: 1.5em 2em;*/
        /*    width: 100%;*/
        /*    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);*/
        /*}*/

        /*.header-left {*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*    color: #ffaa33;*/
        /*}*/

        /*.logo {*/
        /*    width: 40px;*/
        /*    height: auto;*/
        /*    margin-right: 0.5em;*/
        /*}*/

        /*.header-left h1 {*/
        /*    font-size: 1.5em;*/
        /*    margin: 0;*/
        /*}*/

        /*.header-center {*/
        /*    display: flex;*/
        /*    gap: 1.5em;*/
        /*    align-items: center;*/
        /*    text-align: center;*/
        /*}*/

        /*.header-center a {*/
        /*    color: #fff;*/
        /*    text-decoration: none;*/
        /*    font-size: 1em;*/
        /*}*/

        /*.header-center a:hover {*/
        /*    text-decoration: underline;*/
        /*}*/

        .header-right {
            display: block;
            align-items: right;
            text-align: right;
            gap: 1em;
            width: 200px;
        }

        /*.user-icon {*/
        /*    width: 30px;*/
        /*    height: auto;*/
        /*    cursor: pointer;*/
        /*    color: #ffffff;*/
        /*}*/

        .hero-section {
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 100px 20px;
            margin: 0 150px;
        }

        .hero-content-wrapper {
            display: flex;
            flex-direction: column;
        }

        .tabs {
            display: flex;
            width: 100%;
            margin-bottom: 0;
            font-size: 0.8em;
        }

        .tab {
            flex: 1;
            padding: 8px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.4);
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            border-radius: 20px 20px 0 0;
            transition: background-color 0.3s;
        }

        .tab span {
            display: inline-block;
            transition: transform 0.2s, color 0.3s;
        }

        .tab:hover:not(.active) span {
            transform: scale(1.1);
            color: rgba(255, 255, 255, 0.8);
        }

        .tab.active {
            background-color: rgba(0, 0, 0, 0.6);
            color: #ffaa33;
        }

        .hero-content {
            border-radius: 0 0 20px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 30px;
            max-width: 900px;
            margin-right: 0px;
        }

        .hero-content h2 {
            font-size: 1em;
            color: #ccc;
            margin-bottom: 20px;
        }

        .search-container {
            display: flex;
            flex-direction: row;
            gap: 10px;
            margin-top: 20px;
        }

        .search-box {
            display: none;
        }

        .search-box.active {
            display: flex;
        }

        .search-input {
            padding: 10px;
            border: none;
            border-radius: 10px 0 0 10px;
            outline: none;
            color: #000;
            width: 100%;
            max-width: 700px;
            height: 40px;
        }

        .search-btn {
            padding: 10px 20px;
            background-color: #ffaa33;
            color: #fff;
            border: none;
            font-size: 1em;
            cursor: pointer;
            border-radius: 0 10px 10px 0;
            height: 40px;
        }

        .tagline {
            text-align: left;
            max-width: 600px;
            margin-left: 40px;
        }

        .tagline h1 {
            font-size: 4em;
            color: #fff;
            line-height: 1.2em;
            text-shadow: 0 10px 10px rgba(0, 0, 0, 0.7);
        }

        .tagline .highlight {
            color: #ffaa33;
        }

        /* Featured Components Section Styling */
        .featured-components-section {
            margin-top: 2em;
            text-align: center;
            color: #ffffff;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .featured-components-section h2 {
            color: #ffaa33;
            font-size: 1.5em;
            margin-bottom: 1em;
        }

        .featured-components {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.5em;
        }

        .component-card {
            background-color: #2c2c2e;
            padding: 1.5em;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1 1 180px;
            max-width: 220px;
        }

        .component-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        }

        .component-card h3 a {
            color: #ffaa33;
            text-decoration: none;
            font-size: 1.2em;
            font-weight: bold;
        }

        .component-card h3 a:hover {
            text-decoration: underline;
        }

        /* Description with line clamp */
        .component-card .description {
            color: #d1d1d3;
            margin: 0.5em 0;
            font-size: 0.9em;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .component-card p:last-child {
            font-weight: bold;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .hero-section {
                flex-direction: column;
                padding: 50px 10px;
            }

            .hero-content, .tagline {
                text-align: center;
                max-width: 100%;
            }

            .tagline h1 {
                font-size: 2em;
            }

            .search-container {
                flex-direction: column;
                gap: 10px;
            }

            .search-input {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<header>
    <a href="http://nepo.webdev.iyaserver.com/acad276/hardwareHunt/search.php" style="text-decoration: none;">
        <div class="header-left">
            <img src="HHlogo.png" alt="Hardware Hunt Logo" class="logo">
            <h1>Hardware Hunt</h1>
        </div>
    </a>
    <nav class="header-center">
        <a href="#about">About</a>
        <a href="http://nepo.webdev.iyaserver.com/acad276/hardwareHunt/component_results.php">Components</a>
        <a href="#projects">Projects</a>
    </nav>
    <div class="header-right">
        <img src="user-icon.png" alt="User Icon" class="user-icon">
    </div>
</header>

<main>
    <div class="hero-section">
        <div class="hero-content-wrapper">
            <div class="tabs">
                <div class="tab active" onclick="switchTab('parts')"><span>Search Parts</span></div>
                <div class="tab" onclick="switchTab('projects')"><span>Search Projects</span></div>
            </div>
            <div class="hero-content">
                <h2>Skip the hassle of disorganized hardware projects.</h2>
                <div class="search-container">
                    <form action="component_results.php" method="get" class="search-box active" id="parts-search">
                        <input type="text" class="search-input" placeholder="Search for parts" name="search-parts">
                        <button type="submit" class="search-btn">Search</button>
                    </form>
                    <form action="projects_search.php" method="get" class="search-box" id="projects-search">
                        <input type="text" class="search-input" placeholder="Search for projects" name="search-projects">
                        <button type="submit" class="search-btn">Search</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="tagline">
            <h1>COMPONENT DISCOVERY, ALL IN <span class="highlight">ONE</span> PLACE.</h1>
        </div>
    </div>

    <!-- Featured Components Section -->
    <section class="featured-components-section">
        <h2>Featured Components</h2>
        <div class="featured-components">

            <?php
            // Query to fetch 5 random components from the "details" view
            $query = "SELECT component_id, component_name, component_description, component_type, manufacturer_name, price FROM component_details ORDER BY RAND() LIMIT 5";
            $result = $mysql->query($query);

            if ($result) {
                while ($component = $result->fetch_assoc()) {
                    echo "<div class='component-card'>";
                    echo "<h3><a href='component_details.php?id=" . htmlspecialchars($component['component_id']) . "'>" . htmlspecialchars($component['component_name']) . "</a></h3>";
                    echo "<p class='description'>" . htmlspecialchars($component['component_description']) . "</p>";
                    echo "<p>Type: " . htmlspecialchars($component['component_type']) . "</p>";
                    echo "<p>Manufacturer: " . htmlspecialchars($component['manufacturer_name']) . "</p>";
                    echo "<p>Price: $" . number_format($component['price'], 2) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No components found in the 'details' view.</p>";
            }
            ?>

        </div>
    </section>


    </div>
    </section>


    </div>
    </section>


</main>

<script>
function switchTab(type) {
    // Update tabs
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    // Check if the clicked element is the span or the div
    const tabElement = event.target.classList.contains('tab') ? event.target : event.target.parentElement;
    tabElement.classList.add('active');
    
    // Update search boxes
    document.querySelectorAll('.search-box').forEach(box => box.classList.remove('active'));
    if (type === 'parts') {
        document.getElementById('parts-search').classList.add('active');
    } else {
        document.getElementById('projects-search').classList.add('active');
    }
}
</script>

</body>
</html>

