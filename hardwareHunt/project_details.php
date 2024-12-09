<?php
// Start the session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
//
// if I can find an error number then stop because there was a problem
if($mysql->connect_errno) { //if error
    echo "db connection error : " . $mysql->connect_error; //tell me there was an erro
    exit(); //stop running page
} else {
    //echo "db connection success!"; //slaytastic. no errors, removing to get rid of it on page
    //if you mess up username password serve then this error will come up.
}

// Modify the SQL query to get project details
$sql = "SELECT * FROM projects WHERE project_id = " . intval($_REQUEST['id']);
$results = $mysql->query($sql);

if(!$results) {
    echo "<hr>Your SQL:<br> " . $sql . "<br><br>";
    echo "SQL Error: " . $mysql->error . "<hr>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <link rel="stylesheet" href="menu.css">
    <style>
        .container {
            width: 75%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 0;
        }

        .product-details {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
            background-color: #2c2c2e;
            padding: 20px;
            border-radius: 8px;
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
            color: #ffaa33;
        }

        .product-description {
            margin-bottom: 20px;
            color: #ccc;
            line-height: 1.6;
        }

        .price {
            font-size: 1.5em;
            color: #ffaa33;
            margin-bottom: 20px;
        }

        .divider {
            border-top: 1px solid #555;
            margin: 20px 0;
        }

        .details-table {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 10px 20px;
        }

        .details-table .heading {
            color: #888;
            font-weight: bold;
        }

        .link-color {
            color: #ffaa33;
            text-decoration: none;
        }

        .link-color:hover {
            text-decoration: underline;
        }

        .components-section {
            background-color: #2c2c2e;
            padding: 20px;
            border-radius: 8px;
        }

        .components-section h2 {
            color: #ffaa33;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .component {
            padding: 15px;
            border-bottom: 1px solid #555;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .component:last-child {
            border-bottom: none;
        }

        .component-price {
            color: #ffaa33;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            .product-details {
                flex-direction: column;
            }

            .product-image,
            .product-info {
                width: 100%;
            }

            .details-table {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<?php
    $showSearchBar = false;
    include 'navbar.php';
?>
<body>
    <main class="container">
        <div class="product-details">
            <?php
            while($currentrow = $results->fetch_assoc()) {
                ?>
<!--                <img src="project-placeholder.jpg" alt="Project Image" class="product-image">-->
                <div class="product-info">
                    <div class="product-name"><?php echo htmlspecialchars($currentrow['project_name']); ?></div>
                    <div class="product-description"><?php echo htmlspecialchars($currentrow['project_description']); ?></div>
                    <div class="price">Estimated Cost: $<?php echo htmlspecialchars($currentrow['cost']); ?></div>
                    <div class="divider"></div>
                    <div class="details-table">
                        <div class="heading">Documentation</div>
                        <div><a href="<?php echo htmlspecialchars($currentrow['project_documentation_url']); ?>" class="link-color" target="_blank">View Documentation</a></div>
                        <div class="heading">Date Published</div>
                        <div><?php echo htmlspecialchars($currentrow['date']); ?></div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="sections-container">
            <div class="components-section">
                <h2>Required Components</h2>
<!--                --><?php
//                $sql = "SELECT c.* FROM component_details c
//        JOIN `projects-x-components` pc ON c.component_id = pc.component_id
//        WHERE pc.project_id = " . intval($_REQUEST['id']);
//
//                echo "<p>Debug SQL Query: $sql</p>"; // Debug the query
//
//                $components_result = $mysql->query($sql);
//
//                if (!$components_result) {
//                    echo "<p>SQL Error: " . $mysql->error . "</p>";
//                    exit();
//                }
//
//                if ($components_result->num_rows > 0) {
//                    echo "<p>Found " . $components_result->num_rows . " components.</p>"; // Debug number of components found
//                } else {
//                    echo "<p>No components found for this project ID.</p>";
//                }

                                // Query to get components used in this project
                $sql = "SELECT c.* FROM component_details c
                        JOIN `projects-x-components` pc ON c.component_id = pc.component_id
                        WHERE pc.project_id = " . intval($_REQUEST['id']);
                $components_result = $mysql->query($sql);

                if ($components_result && $components_result->num_rows > 0) {
                    while ($component = $components_result->fetch_assoc()) {
                        ?>
                        <div class="component">
                            <a href="component_details.php?id=<?php echo $component['component_id']; ?>" class="link-color">
                                <?php echo htmlspecialchars($component['component_name']); ?>
                            </a>
                            <span class="component-price">$<?php echo htmlspecialchars($component['price']); ?></span>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No components listed for this project.</p>";
                }
                ?>
            </div>
        </div>
    </main>
</body>
</html>
