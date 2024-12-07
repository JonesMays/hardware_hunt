<?php
session_start();
// test comment
// var_dump output variables ot the screen
// request contains all submitted form information
var_dump($_REQUEST);

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
    //echo "db connection success!"; //slaytastic. no errors, removing to get rid of it on page
    //if you mess up username password serve then this error will come up.
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="menu.css">
    <style>
        /** {*/
        /*    box-sizing: border-box;*/
        /*    margin: 0;*/
        /*    padding: 0;*/
        /*}*/

        /*body {*/
        /*    font-family: Arial, sans-serif;*/
        /*    background-color: #1c1c1e;*/
        /*    color: #ffffff;*/
        /*    display: flex;*/
        /*    flex-direction: column;*/
        /*    align-items: center;*/
        /*}*/

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
        /*}*/

        .container {
            width: 75%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 0;
        }

        .product-details {
            display: flex;
            gap: 20px;
        }

        .product-image {
            width: 40%;
            border-radius: 8px;
            margin-right: 30px;
        }

        .product-info {
            width: 60%;
        }

        .product-name {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .product-description {
            margin-bottom: 20px;
            color: #ccc;
        }

        .price {
            font-size: 1.5em;
            color: #ffaa33;
            margin-bottom: 10px;
        }

        .stock-status {
            color: white;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .cart-section {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .buy-btn {
            background-color: #ffaa33;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .add-to-fav-btn {
            background-color: #ffaa33;
            color: #ffaa33;
            padding: 10px 10px 10px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            text-align: left;
        }
        /*amanda added this: */
        .add-to-fav-btn:hover {
            background-color: #cc8800; /* Darker shade when hovered */
        }
        .add-to-fav-btn.added {
            color: white;
        }

        .quantity-input {
            padding: 10px;
            width: 50px;
            border: 1px solid #555;
            border-radius: 5px;
            color: #000;
            text-align: center;
        }

        .divider {
            border-top: 1px solid #555;
            margin: 20px 0;
        }

        .details-table {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 20px;
        }

        .details-table div {
            padding: 5px 0;
        }

        .details-table .heading {
            color: #888;
            font-weight: bold;
        }

        .reviews-section, .projects-section {
            width: 48%;
            padding: 20px;
            border-radius: 8px;
            background-color: #2c2c2e;
        }

        .reviews-section h2, .projects-section h2 {
            color: #ffaa33;
            margin-bottom: 10px;
        }

        .projects-section {
            height: auto;
        }

        .review, .project {
            padding: 10px 0;
            border-bottom: 1px solid #555;
        }

        .review:last-child, .project:last-child {
            border-bottom: none;
        }

        .review-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }

        .user-icon-small {
            width: 30px;
            height: auto;
            border-radius: 50%;
        }

        .user-name {
            font-weight: bold;
            color: #fff;
        }

        .rating {
            color: #ffaa33;
            font-size: 1em;
        }

        .review-text {
            color: #ccc;
            margin-top: 5px;
        }

        .sections-container {
            display: flex;
            gap: 40px;
            margin-top: 40px;
        }

        .write-review-btn {
            background-color: #ffaa33;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin-top: 10px;
        }

        .review-form {
            display: none;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
            padding: 15px;
            background-color: #333;
            border-radius: 8px;
        }

        .review-form label {
            color: #ffaa33;
        }

        .review-form select, .review-form textarea {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #555;
            background-color: #2c2c2e;
            color: #fff;
            width: 100%;
        }

        .review-form button {
            align-self: flex-end;
            background-color: #ffaa33;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .link-color {
            color: #ffaa33;
        }
        
        @media (max-width: 768px) {
            .product-details, .sections-container {
                flex-direction: column;
            }
            .product-image, .product-info, .reviews-section, .projects-section {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<header>
    <!--
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
    -->
    <?php include 'navbar.php'; ?>

</header>

<main class="container">
    <div class="product-details">
        <?php
        $sql = "SELECT * from component_details WHERE component_id = " . $_REQUEST['id'];
        $results = $mysql->query($sql);

        if(!$results) {
            echo "<hr>Your SQL:<br> " . $sql . "<br><br>";
            echo "SQL Error: " . $mysql->error . "<hr>";
            exit();
        }

        while($currentrow = $results->fetch_assoc()) {
            ?>
            <img src="<?php echo $currentrow['component_image']; ?>" alt="Product Image" class="product-image">
            <div class="product-info">
                <div class="product-name"><?php echo $currentrow['component_name']; ?></div>
                <div class="product-description"><?php echo $currentrow['component_description']; ?></div>
                <div class="price"> $<?php echo $currentrow['price']; ?></div>
                <div class="stock-status">Stock Quantity: <?php echo $currentrow['stock_quantity']; ?></div>
                <div class="cart-section">
                    <a href="<?php echo $currentrow['purchase_link']; ?>" class="buy-btn" style="text-decoration: none;">Buy Now</a>
                    <button class="add-to-fav-btn" style="background-color: transparent;" id="favoritesBtn">Add to Favorites</button>
                </div>
                <div class="divider"></div>
                <div class="details-table">
                    <div class="heading">Manufacturer</div>
                    <div><?php echo $currentrow['manufacturer_name']; ?></div>
                    <div class="heading">Category</div>
                    <div><?php echo $currentrow['component_type']; ?> Boards</div>
                    <div class="heading">Data Sheet</div>
                    <div><a href="<?php echo $currentrow['spec_sheet_url']; ?>" class="link-color">Download</a></div>
                    <div class="heading">Video Tutorial</div>
                    <div><?php
                        // Assuming $currentrow['tutorial_url'] contains the video URL
                        $videoUrl = $currentrow['tutorial_url'];

                        // Extract the video ID for YouTube
                        function extractYouTubeID($url) {
                            parse_str(parse_url($url, PHP_URL_QUERY), $query);
                            return $query['v'] ?? ''; // Extract the 'v' parameter, which is the video ID
                        }

                        $videoID = extractYouTubeID($videoUrl);

                        // Check if the video ID is valid
                        if (!empty($videoID)) {
                            echo '<a href="' . htmlspecialchars($videoUrl) . '" target="_blank">';
                            echo '<img src="https://img.youtube.com/vi/' . htmlspecialchars($videoID) . '/hqdefault.jpg" alt="Tutorial Thumbnail" style="width: 200px; height: auto;">';
                            echo '</a>';
                        } else {
                            // Fallback in case the URL is not a YouTube link
                            echo '<a href="' . htmlspecialchars($videoUrl) . '" target="_blank" class="link-color">Watch</a>';
                        }
                        ?></div>
                </div>
            </div>
        <?php  }?>
    </div>

    <div class="sections-container">
        <div class="projects-section">
            <h2>Projects</h2>
            <!-- Placeholder project content -->
<!--            <div class="project">Project 1: Smart IoT Device</div>-->
<!--            <div class="project">Project 2: Environmental Monitoring</div>-->
<!--            <div class="project">Project 3: Wearable Tech Prototype</div>-->
            <?php


            // Step 1: Query the projects-x-components table to get project_id(s) for the specified component_id
            $sql = "SELECT project_id FROM `projects-x-components` WHERE component_id = " . $_REQUEST['id'];
            $results = $mysql->query($sql);


            if (!$results) {
                echo "<hr>Your SQL:<br> " . $sql . "<br><br>";
                echo "SQL Error: " . $mysql->error . "<hr>";
                exit();
            }


            $project_ids = []; // Initialize an array to store project_ids


            while ($currentrow = $results->fetch_assoc()) {
                $project_ids[] = $currentrow['project_id']; // Save each project_id to the array
            }


            // Step 2: If there are project_ids, build a query to get project details from the projects table
            if (!empty($project_ids)) {
                // Join the project_ids array into a comma-separated string for the SQL IN clause
                $project_ids_str = implode(",", $project_ids);


                $sql = "SELECT * FROM projects WHERE project_id IN ($project_ids_str)";
                $project_results = $mysql->query($sql);


                if (!$project_results) {
                    echo "<hr>Your SQL:<br> " . $sql . "<br><br>";
                    echo "SQL Error: " . $mysql->error . "<hr>";
                    exit();
                }

                echo "<hr>";
                // Step 3: Fetch and display project details
                while ($project_row = $project_results->fetch_assoc()) {
                    echo "<div class='project'>";
                    echo "<a href='project_details.php?project_id=" . $project_row['project_id']
                        . "' class='link-color'>" . $project_row['project_name'] . "</a>";
                    echo "</div><hr>";
                }

            } else {
                echo "No projects found for this component.";
            }
            ?>
</div>

        <div class="reviews-section">
            <h2>User Reviews</h2>
            <?php
            // Step 1: grab reviews with the component id of the details page selected
            $sql = "SELECT r.component_id, r.review_id, r.user_id, r.project_id, r.rating_value, r.review_comments, r.created_at, u.username 
        FROM reviews r 
        JOIN users u ON r.user_id = u.user_id 
        WHERE r.component_id = " . intval($_REQUEST['id']); // Use JOIN to fetch username in a single query

            $results = $mysql->query($sql);

            if (!$results) {
                echo "<hr>Your SQL:<br> " . $sql . "<br><br>";
                echo "SQL Error: " . $mysql->error . "<hr>";
                exit();
            }

            if ($results->num_rows > 0) {
                while ($currentrow = $results->fetch_assoc()) {
                    ?>
                    <div class="review">
                        <div class="review-header">
                            <img src="user-icon-small.png" alt="User Icon" class="user-icon-small">
                            <span class="user-name"><?php echo htmlspecialchars($currentrow['username']); ?></span> <!-- Display username -->
                            <span class="rating"><?php echo str_repeat('★', $currentrow['rating_value']) . str_repeat('☆', 5 - $currentrow['rating_value']); ?></span>
                        </div>
                        <p class="review-text"><?php echo htmlspecialchars($currentrow['review_comments']); ?></p>
<!--                        maybe fix ui so there is a date as well? amanda-->
<!--                        <span class="review-date">--><?php //echo htmlspecialchars($currentrow['created_at']); ?><!--</span> Display date -->
                    </div>
                    <?php
                }
            } else {
                echo "No reviews found for this component.";
            }
            ?>
            <!-- Placeholder review content -->
            <div class="review">
                <div class="review-header">
                    <img src="user-icon-small.png" alt="User Icon" class="user-icon-small">
                    <span class="user-name">Jane Doe</span>
                    <span class="rating">★★★★☆</span>
                </div>
                <p class="review-text">Great component! Worked perfectly for my IoT project.</p>
            </div>

            <div class="review">
                <div class="review-header">
                    <img src="user-icon-small.png" alt="User Icon" class="user-icon-small">
                    <span class="user-name">Bryson Chan</span>
                    <span class="rating">★★★★★</span>
                </div>
                <p class="review-text">Very versatile and easy to use.</p>
            </div>

            <!-- Write a Review button -->
            <button class="write-review-btn" onclick="toggleReviewForm()">Write a Review</button>

            <!-- Review Form -->
            <div class="review-form" id="reviewForm">
                <label for="rating">Rating:</label>
                <select id="rating" name="rating">
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Very Poor</option>
                </select>

                <label for="review">Your Review:</label>
                <textarea id="review" name="review" rows="4" placeholder="Write your review here..."></textarea>

                <button onclick="submitReview()">Submit Review</button>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const favoritesBtn = document.getElementById('favoritesBtn');
        
        favoritesBtn.addEventListener('click', function() {
            if (this.classList.contains('added')) {
                this.textContent = 'Add to Favorites';
                this.classList.remove('added');
            } else {
                this.textContent = 'Remove from Favorites';
                this.classList.add('added');
            }
        });
    });
</script>

</body>
</html>

