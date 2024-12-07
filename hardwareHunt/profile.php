<?php
session_start();
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

// Get user's projects
$user_id = $_SESSION['user_id'] ?? null;
$projects_query = "SELECT * FROM projects WHERE user_id = ? OR user_id IS NULL ORDER BY date DESC LIMIT 5";
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
    </style>
</head>
<body>

<iframe src="NewPages/navbar.html" style="border: none; width: 100%; height: auto;" id="navbar"></iframe>

<main>
    <div class="profile-container">
        <?php
        echo "<h1>Hi, " . $_SESSION["user_first_name"] . "</h1>";
        ?>
        <div class="user-details">
            <div>
                <?php
                echo "<p><strong>Name:</strong> <span id='name-display'>"
                . $_SESSION["user_first_name"] . " " . $_SESSION["user_last_name"] .
                "</span></p>";
                ?>
                <input type="text" id="name-input" class="edit-input" value="<?php echo $_SESSION["user_first_name"]; ?>">
                <span class="edit-icon" id="edit-name" onclick="editField('name')">&#9998;</span>
                <button class="save-button" id="save-name" onclick="saveField('name')">Save</button>
            </div>
            <div>
                <?php
                echo "<p><strong>Email:</strong> <span id='email-display'>"
                . $_SESSION["user_email"] . "</span></p>";
                ?>
                <input type="text" id="email-input" class="edit-input" value="<?php echo $_SESSION['user_email']; ?>">
                <span class="edit-icon" id="edit-email" onclick="editField('email')">&#9998;</span>
                <button class="save-button" id="save-email" onclick="saveField('email')">Save</button>
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
                                <img src="/api/placeholder/200/150" alt="Project Image">
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
            <button class="add-project-button">Add Project</button>
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