<?php

// Start the session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Check if logout request is made
if (isset($_GET['logout'])) {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Destroy the session
    session_destroy();

    // Redirect to the home page
    header("Location: search.php");
    exit();
}
?>

<?php



// Database connection code remains the same
$host = "webdev.iyaserver.com";
$userid = "nepo";
$userpw = "BackendMagic1024";
$db = "nepo_hardwareHunt2";

$mysql = new mysqli(
    $host,
    $userid,
    $userpw,
    $db
);

if($mysql->connect_errno) {
    echo "db connection error : " . $mysql->connect_error;
    exit();
}

//Retrieve Profile Info from database
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_user_id"] ?? null;
    $field = $_POST["field"] ?? null;
    $value = $_POST["value"] ?? null;

    // Validate field and update database
    $allowed_fields = ["first_name", "last_name", "email", "password"];
    if ($user_id && $field && in_array($field, $allowed_fields)) {
        $update_query = "UPDATE users SET $field = ? WHERE user_id = ?";
        $stmt = $mysql->prepare($update_query);
        $stmt->bind_param("si", $value, $user_id);

        if ($stmt->execute()) {
            // Update session variable for immediate reflection
            $_SESSION["user_" . $field] = $value;
        } else {
            echo "Error updating $field.";
        }
    }

    // Reload the page using HTML meta refresh
    echo '<meta http-equiv="refresh" content="0">';
    exit();
}

// Get user's projects
$user_id = $_SESSION['user_user_id'] ?? null;
$projects_query = "SELECT * FROM projects WHERE user_id = ? /* OR user_id IS NULL ORDER BY date DESC LIMIT 5*/";
$stmt = $mysql->prepare($projects_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
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

        .profile-container {
            background-color: #2c2c2e;
            padding: 2em;
            border-radius: 10px;
            width: 100%;
            max-width: 800px;
            margin: 2em auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .profile-container h1 {
            color: #ffaa33;
            margin-bottom: 1em;
            text-align: center;
        }

        .admin-button {
            position: absolute;
            top: 2em;
            right: 2em;
            background-color: #ffaa33;
            color: #1c1c1e;
            padding: 0.5em 1em;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .admin-button:hover {
            background-color: #e5992e;
        }

        .profile-container {
            position: relative;  /* Add this to the existing profile-container style */
        }

        .user-details {
            margin-bottom: 2em;
        }

        .user-details div {
            display: flex;
            align-items: center;
            margin-bottom: 1em;
        }

        .user-details p {
            font-size: 1.2em;
            margin: 0;
            flex-grow: 1;
        }

        .edit-icon {
            cursor: pointer;
            margin-left: 10px;
            color: #ffaa33;
            font-size: 1.2em;
        }

        .edit-input {
            width: 70%;
            padding: 0.5em;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #3a3a3c;
            color: #ffffff;
            display: none;
        }

        .save-button {
            background-color: #ffaa33;
            border: none;
            padding: 0.5em 1em;
            border-radius: 5px;
            color: #1c1c1e;
            font-size: 0.9em;
            cursor: pointer;
            display: none;
        }

        .projects-section {
            margin-top: 2em;
        }

        .projects-section h2 {
            color: #ffaa33;
            margin-bottom: 1em;
        }

        .project-list {
            width: 100%;
            padding: 1.5em;
            background-color: #1c1c1e;
            border-radius: 10px;
        }

        .project-item {
            display: flex;
            align-items: center;
            background-color: #2c2c2e;
            border-radius: 10px;
            padding: 1em;
            margin-bottom: 1em;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            transition: transform 0.2s ease-in-out;
        }

        .project-item:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .project-item img {
            width: 20%;
            height: auto;
            border-radius: 5px;
            margin-right: 1em;
        }

        .project-details {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .project-info {
            display: flex;
            flex-direction: column;
        }

        .project-info h3 {
            font-size: 1.1em;
            color: #ffffff;
            margin: 0;
        }

        .project-info p {
            font-size: 0.9em;
            color: #a1a1a3;
            margin: 0.3em 0;
        }

        .add-project-button {
            background-color: #ffaa33;
            border: none;
            padding: 0.75em 1.5em;
            border-radius: 5px;
            color: #1c1c1e;
            font-size: 1em;
            cursor: pointer;
            display: block;
            margin: 1em auto 0;
        }

        .add-project-button:hover {
            background-color: #e5992e;
        }
        
        .logout-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .logout-button:hover {
            background-color: #e60000;
        }

    </style>
</head>
<?php
    $showSearchBar = false;
    include 'navbar.php';
?>
<body>
<main>
    <div class="profile-container">
        <?php
        if(isset($_SESSION['user_admin']) && $_SESSION['user_admin'] == 1) {
            echo '<a href="HH Admin Website/index.php" class="admin-button">Admin Panel</a>';
        }
        ?>
        <?php
        
        
        echo "<h1>Hi, " . $_SESSION["user_first_name"] . "</h1>";
        ?>
        <div class="user-details">
            
            <form action="profile.php" method="get">
                <button type="submit" name="logout" value="true" class="logout-button">
                    Log Out
                </button>
            </form>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <?php
                echo "<p style='flex-grow: 1;'><strong>First Name:</strong> <span id='first_name-display'>"
                . $_SESSION["user_first_name"] . "</span></p>";
                ?>
                <form method="post" action="" style="display: flex; align-items: center;">
                    <span class="edit-icon" id="edit-first_name" onclick="editField('first_name')" 
                          style="margin-right: 10px; cursor: pointer;">&#9998;</span>
                    <input type="hidden" name="field" value="first_name">
                    <input type="text" id="first_name-input" class="edit-input" name="value" 
                           value="<?php echo $_SESSION["user_first_name"]; ?>" 
                           style="margin-right: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; display: none;">
                    <button class="save-button" id="save-first_name" type="submit" 
                            style="padding: 5px 10px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; display: none;">
                        Save
                    </button>
                </form>
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <?php
                echo "<p style='flex-grow: 1;'><strong>Last Name:</strong> <span id='last_name-display'>"
                . $_SESSION["user_last_name"] . "</span></p>";
                ?>
                <form method="post" action="" style="display: flex; align-items: center;">
                    <span class="edit-icon" id="edit-last_name" onclick="editField('last_name')" 
                          style="margin-right: 10px; cursor: pointer;">&#9998;</span>
                    <input type="hidden" name="field" value="last_name">
                    <input type="text" id="last_name-input" class="edit-input" name="value" 
                           value="<?php echo $_SESSION["user_last_name"]; ?>" 
                           style="margin-right: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; display: none;">
                    <button class="save-button" id="save-last_name" type="submit" 
                            style="padding: 5px 10px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; display: none;">
                        Save
                    </button>
                </form>
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <?php
                echo "<p style='flex-grow: 1;'><strong>Email:</strong> <span id='email-display'>"
                . $_SESSION["user_email"] . "</span></p>";
                ?>
                <form method="post" action="" style="display: flex; align-items: center;">
                    <span class="edit-icon" id="edit-email" onclick="editField('email')" 
                          style="margin-right: 10px; cursor: pointer;">&#9998;</span>
                    <input type="hidden" name="field" value="email">
                    <input type="email" id="email-input" class="edit-input" name="value" 
                           value="<?php echo $_SESSION["user_email"]; ?>" 
                           style="margin-right: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; display: none;">
                    <button class="save-button" id="save-email" type="submit" 
                            style="padding: 5px 10px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; display: none;">
                        Save
                    </button>
                </form>
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <?php
                echo "<p style='flex-grow: 1;'><strong>Password:</strong> <span id='password-display'>"
                . htmlspecialchars($_SESSION["user_password"]) . "</span></p>";
                ?>
                <form method="post" action="" style="display: flex; align-items: center;">
                    <span class="edit-icon" id="edit-password" onclick="editField('password')" 
                          style="margin-right: 10px; cursor: pointer;">&#9998;</span>
                    <input type="hidden" name="field" value="password">
                    <input type="text" id="password-input" class="edit-input" name="value" 
                           value="<?php echo htmlspecialchars($_SESSION['user_password'] ?? ''); ?>" 
                           style="margin-right: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; display: none;">
                    <button class="save-button" id="save-password" type="submit" 
                            style="padding: 5px 10px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; display: none;">
                        Save
                    </button>
                </form>
            </div>
        </div>
        <div class="projects-section">
            <h2>Your Projects</h2>
            <div class="project-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($project = $result->fetch_assoc()) {
                        ?>
                        <a href="project_details.php?id=<?php echo htmlspecialchars($project['project_id']); ?>" style="text-decoration: none; color: inherit;">
                            <div class="project-item">
<!--                                <img src="/api/placeholder/200/150" alt="Project Image">-->
                                <div class="project-details">
                                    <div class="project-info">
                                        <h3><?php echo htmlspecialchars($project['project_name']); ?></h3>
                                        <p><?php echo htmlspecialchars($project['project_description']); ?></p>
                                        <p>Date: <?php echo date('m/d/Y', strtotime($project['date'])); ?></p>
                                        <p>Cost: $<?php echo htmlspecialchars($project['cost']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <?php
                    }
                } else {
                    echo "<p>No projects found.</p>";
                }
                ?>
            </div>
            <button class="add-project-button" onclick="location.href='addProject.php'">Add Project</button>
        </div>
    </div>
</main>

<script>
    // Your existing JavaScript remains the same
    function editField(field) {
        const displayElement = document.getElementById(`${field}-display`);
        const inputElement = document.getElementById(`${field}-input`);
        const editIcon = document.getElementById(`edit-${field}`);
        const saveButton = document.getElementById(`save-${field}`);

        displayElement.style.display = 'none';
        inputElement.style.display = 'inline-block';
        editIcon.style.display = 'none';
        saveButton.style.display = 'inline-block';
    }

    function saveField(field) {
        const displayElement = document.getElementById(`${field}-display`);
        const inputElement = document.getElementById(`${field}-input`);
        const editIcon = document.getElementById(`edit-${field}`);
        const saveButton = document.getElementById(`save-${field}`);

        displayElement.textContent = inputElement.value;
        displayElement.style.display = 'inline-block';
        inputElement.style.display = 'none';
        editIcon.style.display = 'inline-block';
        saveButton.style.display = 'none';
    }
</script>
</body>
</html>
