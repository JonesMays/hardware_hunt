<?php
session_start();
// var_dump output variables ot the screen
// request contains all submitted form information
// var_dump($_REQUEST);

// echo "<br><br>hello " . $_REQUEST["fullname"];//

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
<?php
$showSearchBar = false;
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Projects</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chosen-js@1.8.7/chosen.min.css">
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
            align-items: center;
        }

        .form-container {
            background-color: #2c2c2e;
            padding: 2em;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            display: block;
            margin: auto;

        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 1em;
            color: #ffaa33;
        }
        .form-group {
            margin-bottom: 1.5em;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5em;
            font-weight: bold;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 0.75em;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #3a3a3c;
            color: #ffffff;
        }
        .form-group input[type="file"] {
            padding: 0.5em;
        }
        .form-group textarea {
            resize: vertical;
            height: 100px;
        }
        .form-group input::placeholder {
            color: #a1a1a3;
        }
        .form-group select {
            appearance: none;
        }
        .form-actions {
            text-align: center;
        }
        .form-actions button {
            background-color: #ffaa33;
            border: none;
            padding: 0.75em 1.5em;
            border-radius: 5px;
            color: #1c1c1e;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }
        .form-actions button:hover {
            background-color: #e5992e;
        }

    </style>
</head>
<body>

<!--<iframe src="NewPages/navbar.html" style="border: none; width: 100%; height: auto;" id="navbar"></iframe>-->
<br>
<main class="container">
    <div class="form-container" style="width: 90%; max-width: 800px;">
        <h2>Add a New Project</h2>
        <form method = "POST" action="">
            <div class="form-group">
                <label for="project-title">Project Title</label>
                <input type="text" id="project-title" name="project-title" placeholder="Enter project title" required>
            </div>
            <div class="form-group">
                <label for="project-description">Project Description</label>
                <textarea id="project-description" name="project-description" placeholder="Describe your project" required></textarea>
            </div>
<!--            <div class="form-group">-->
<!--                <label for="category">Category</label>-->
<!--                <input type="text" id="category" name="category" placeholder="Enter category" required>-->
<!--            </div>-->
<!--            <div class="form-group">-->
<!--                <label for="hero-image">Hero Image</label>-->
<!--                <input type="file" id="hero-image" name="hero-image" accept="image/*" required>-->
<!--            </div>-->
            <div class="form-group">
                <label for="components-used">Components Used</label>
                <select id="components-used" name="components-used[]" multiple class="chosen-select" required>
                    <?php
                    $sql = "SELECT * FROM component_details WHERE 1=1";

                    $results = $mysql->query($sql);

                    if(!$results) {
                        echo "SQL error: ". $mysql->error;
                        exit();
                    }

                    while ($currentrow = $results->fetch_assoc()) {
                        echo '<div class="filter-option">';
                        echo '<option value="' .
                            $currentrow['component_id'] . '"> ' .
                            $currentrow['component_name'] . '</option>';
                        echo '</div>';
                    }
                    ?>
<!--                    <option value="component1">Component 1</option>-->
<!--                    <option value="component2">Component 2</option>-->
<!--                    <option value="component3">Component 3</option>-->
<!--                    <option value="component4">Component 4</option>-->
                </select>
            </div>
<!--            <div class="form-group">-->
<!--                <label for="supporting-images">Supporting Images</label>-->
<!--                <input type="file" id="supporting-images" name="supporting-images" accept="image/*" multiple>-->
<!--            </div>-->
            <div class="form-group">
                <label for="project-url">Project Documentation URL</label>
                <input type="text" id="project-url" name="project-url" placeholder="Enter project documentation url" required>
            </div>
            <div class="form-actions">
                <button type="submit" onclick="location.href='profile.php'">Submit</button>
            </div>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ensure user is logged in
        if (!isset($_SESSION['user_user_id'])) {
            echo "<script>alert('You need to be logged in to submit a project.');</script>";
            exit();
        }

        // Get form data
        $user_id = intval($_SESSION['user_user_id']);
        $project_name = $mysql->real_escape_string($_POST['project-title']);
        $project_description = $mysql->real_escape_string($_POST['project-description']);
        $project_url = $mysql->real_escape_string($_POST['project-url']);
        $components_used = $_POST['components-used'] ?? []; // Handle empty selection

        // Ensure components are selected
        if (empty($components_used)) {
            echo "<script>alert('Please select at least one component.');</script>";
            exit();
        }
// Calculate total cost based on selected components
        $component_ids = implode(",", array_map('intval', $components_used)); // Sanitize IDs for SQL query
        $sql = "SELECT SUM(price) AS total_cost FROM component_details WHERE component_id IN ($component_ids)";
        $result = $mysql->query($sql);

        if (!$result) {
            echo "SQL Error: " . $mysql->error;
            exit();
        }

        $row = $result->fetch_assoc();
        $total_cost = $row['total_cost'];

        // Insert the new project into the projects table
        $sql = "
        INSERT INTO projects (user_id, project_name, project_description, date, project_documentation_url, cost)
        VALUES ($user_id, '$project_name', '$project_description', CURDATE(), '$project_url', $total_cost)
    ";
        $result = $mysql->query($sql);

        if (!$result) {
            echo "Error inserting project: " . $mysql->error;
            exit();
        }
        //insert userxprojects
        // Get the auto-generated project_id
        $project_id = $mysql->insert_id;
        $sql = "
        INSERT INTO `user-x-projects` (user_id, project_id)
        VALUES ($user_id, $project_id)
        ";

        $result = $mysql->query($sql);

        if (!$result) {
            echo "Error inserting user-project mapping: " . $mysql->error; // CHANGED
            exit();
        }

//        var_dump($project_id, $components_used);
        // Insert selected components into the `projects-x-components` table
        foreach ($components_used as $component_id) {
            $component_id = intval($component_id); // Ensure the ID is an integer
            $sql = "INSERT INTO `projects-x-components` (project_id, component_id) VALUES ($project_id, $component_id)";
            $result = $mysql->query($sql);

            echo "SQL: " . $sql . "<br>"; // Print the query
            if (!$result) {
                echo "Error inserting project components: " . $mysql->error . "<br>";
            } else {
                echo "Inserted component ID $component_id for project ID $project_id<br>";
            }
        }

//        foreach ($components_used as $component_id) {
//            $sql = "INSERT INTO `projects-x-components` (project_id, component_id) VALUES ($project_id, " . intval($component_id) . ")";
//            $result = $mysql->query($sql);
//
//            if (!$result) {
//                echo "Error inserting project components: " . $mysql->error;
//                exit();
//            }
//        }

        echo "<script>alert('Project added successfully!'); window.location.href = 'profile.php';</script>"; // CHANGED
    }
    ?>


</main>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chosen-js@1.8.7/chosen.jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Apply chosen.js to multi-select
        $(".chosen-select").chosen({
            placeholder_text_multiple: "Select components",
            search_contains: true,
            no_results_text: "No components found!"
        });
    });
</script>

</body>
</html>
