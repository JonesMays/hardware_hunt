<?php
// Start the session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// test comment
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

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_review') {
    // Verify user is logged in
    if (!isset($_SESSION["user_user_id"])) {
        echo "User must be logged in to submit review";
        exit;
    }
    
    // Validate inputs
    $rating = intval($_POST['rating']);
    $review = trim($_POST['review']);
    $component_id = intval($_POST['component_id']);
    $user_id = intval($_SESSION["user_user_id"]);
    
    // Check if user has already reviewed this component
    $check_sql = "SELECT review_id FROM reviews WHERE user_id = ? AND component_id = ?";
    $check_stmt = $mysql->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $component_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        echo "You have already reviewed this component";
        $check_stmt->close();
        exit;
    }
    $check_stmt->close();
    
    // Insert the review
    $sql = "INSERT INTO reviews (component_id, user_id, rating_value, written_review) 
            VALUES (?, ?, ?, ?)";
            
    $stmt = $mysql->prepare($sql);
    $stmt->bind_param("iiis", $component_id, $user_id, $rating, $review);
    
    if ($stmt->execute()) {
        // Redirect back to the same page
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $component_id);
        exit;
    } else {
        echo "Error saving review: " . $mysql->error;
        exit;
    }
}

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

<?php
    $showSearchBar = false;
    include 'navbar.php';
?>
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
                    <!-- <button class="add-to-fav-btn" style="background-color: transparent;" id="favoritesBtn">Add to Favorites</button> -->
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
            // Single efficient query to get project details
            $sql = "SELECT p.* FROM projects p 
                    JOIN `projects-x-components` pc ON p.project_id = pc.project_id 
                    WHERE pc.component_id = " . intval($_REQUEST['id']);

            $results = $mysql->query($sql);

            if (!$results) {
                echo "<hr>Your SQL:<br> " . $sql . "<br><br>";
                echo "SQL Error: " . $mysql->error . "<hr>";
                exit();
            }

            if ($results->num_rows > 0) {
                while ($project_row = $results->fetch_assoc()) {
                    echo "<div class='project'>";
                    echo "<a href='project_details.php?id=" . $project_row['project_id'] 
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
            $sql = "SELECT r.component_id, r.review_id, r.user_id, r.rating_value, r.written_review, u.first_name 
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
                            <span class="user-name"><?php echo htmlspecialchars($currentrow['first_name']); ?></span> <!-- Display username -->
                            <span class="rating"><?php echo str_repeat('★', $currentrow['rating_value']) . str_repeat('☆', 5 - $currentrow['rating_value']); ?></span>
                        </div>
                        <p class="review-text"><?php echo htmlspecialchars($currentrow['written_review']); ?></p>
<!--                        maybe fix ui so there is a date as well? amanda-->
<!--                        <span class="review-date">--><?php //echo htmlspecialchars($currentrow['created_at']); ?><!--</span> Display date -->
                    </div>
                    <?php
                }
            } else {
                echo "No reviews found for this component.<br>";
            }
            ?>

            <?php if (isset($_SESSION["user_user_id"])) { ?>
                <script>
                    window.userFirstName = <?php echo json_encode($_SESSION["user_first_name"]); ?>;
                </script>
            <?php } ?>

            <!-- Write a Review button -->
            <?php
            if (isset($_SESSION["user_user_id"])) {
                // Check if user has already reviewed this component
                $check_sql = "SELECT review_id FROM reviews WHERE user_id = ? AND component_id = ?";
                $check_stmt = $mysql->prepare($check_sql);
                $check_stmt->bind_param("ii", $_SESSION["user_user_id"], $_REQUEST['id']);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result->num_rows > 0) {
                    echo '<div style="margin-top: 20px; text-align: center; color: #ffaa33;">
                            Thank you for submitting a review!
                          </div>';
                } else {
                    // User is logged in and hasn't reviewed yet, show the button
                    echo '<button class="write-review-btn" onclick="toggleReviewForm()">Write a Review</button>';
                    
                    // Show the review form (it will still be hidden by default due to CSS)
                    echo '<form class="review-form" id="reviewForm" method="POST">
                            <input type="hidden" name="action" value="submit_review">
                            <input type="hidden" name="component_id" value="' . $_REQUEST['id'] . '">
                            
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
            
                            <button type="submit">Submit Review</button>
                        </form>';
                }
                $check_stmt->close();
            } else {
                // User is not logged in, show a message with a link to login
                echo '<div style="margin-top: 20px; text-align: center;">
                        <a href="login.php" class="link-color" style="text-decoration: none;">
                            Login to write a review
                        </a>
                      </div>';
            }
            ?>
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

    function toggleReviewForm() {
        const reviewForm = document.getElementById('reviewForm');
        const writeReviewBtn = document.querySelector('.write-review-btn');
        
        // Check if the form is currently hidden
        const isFormHidden = reviewForm.style.display === 'none' || reviewForm.style.display === '';
        
        if (isFormHidden) {
            // Show the form
            reviewForm.style.display = 'flex';
            writeReviewBtn.textContent = 'Cancel Review';
        } else {
            // Hide the form
            reviewForm.style.display = 'none';
            writeReviewBtn.textContent = 'Write a Review';
            // Reset the form
            document.getElementById('rating').value = '5';
            document.getElementById('review').value = '';
        }
    }

    /*
    function submitReview() {
        const rating = document.getElementById('rating').value;
        const review = document.getElementById('review').value;
        const componentId = new URLSearchParams(window.location.search).get('id');
        
        // Basic validation
        if (!review.trim()) {
            alert('Please write a review before submitting.');
            return;
        }
        
        // Create form data
        const formData = new FormData();
        formData.append('action', 'submit_review');
        formData.append('rating', rating);
        formData.append('review', review);
        formData.append('component_id', componentId);
        
        // Submit the review
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Create new review element
                const reviewsSection = document.querySelector('.reviews-section');
                const newReview = document.createElement('div');
                newReview.className = 'review';
                newReview.innerHTML = `
                    <div class="review-header">
                        <img src="user-icon-small.png" alt="User Icon" class="user-icon-small">
                        <span class="user-name">${window.userFirstName || 'You'}</span>
                        <span class="rating">${'★'.repeat(rating)}${'☆'.repeat(5-rating)}</span>
                    </div>
                    <p class="review-text">${review}</p>
                `;
                
                // Insert new review after the h2 title
                const h2Element = reviewsSection.querySelector('h2');
                h2Element.insertAdjacentElement('afterend', newReview);
                
                // Remove the form and button
                const reviewForm = document.getElementById('reviewForm');
                const writeReviewBtn = document.querySelector('.write-review-btn');
                
                if (reviewForm) reviewForm.remove();
                if (writeReviewBtn) writeReviewBtn.remove();
                
                // Add thank you message
                const thankYouMessage = document.createElement('div');
                thankYouMessage.style.marginTop = '20px';
                thankYouMessage.style.textAlign = 'center';
                thankYouMessage.style.color = '#ffaa33';
                thankYouMessage.textContent = 'Thank you for submitting a review!';
                
                // Insert the thank you message where the button was
                h2Element.insertAdjacentElement('afterend', thankYouMessage);
                
            } else {
                alert(data.message || 'Error submitting review. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error submitting review. Please try again.');
        });
    */
</script>

</body>
</html>

