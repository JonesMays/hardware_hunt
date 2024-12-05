<?php
require_once 'db_connect.php';
include 'admin_navbar.php';
$table = $_GET['table'] ?? '';

if (!$table) {
    die("Table not specified.");
}

// Fetch the primary key dynamically
$primaryKeyQuery = "SELECT COLUMN_NAME 
                    FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                      AND TABLE_NAME = ? 
                      AND COLUMN_KEY = 'PRI'";
$stmt = $conn->prepare($primaryKeyQuery);
$stmt->bind_param('s', $table);
$stmt->execute();
$primaryKeyResult = $stmt->get_result();

if ($primaryKeyResult->num_rows === 0) {
    die("No primary key found for table: " . htmlspecialchars($table));
}
$primaryKey = $primaryKeyResult->fetch_assoc()['COLUMN_NAME'];

// Fetch the maximum value for the primary key
$maxQuery = "SELECT MAX(`$primaryKey`) AS max_id FROM `$table`";
$maxResult = $conn->query($maxQuery);
$newId = ($maxResult && $row = $maxResult->fetch_assoc()) ? $row['max_id'] + 1 : 1;

// Fetch column information for the table
$columnsQuery = "DESCRIBE `$table`";
$columnsResult = $conn->query($columnsQuery);

if (!$columnsResult) {
    die("Error fetching columns: " . $conn->error);
}

$columns = [];
while ($row = $columnsResult->fetch_assoc()) {
    $columns[] = $row;
}

// Success message trigger
$showSuccessMessage = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = array_keys($_POST);
    $values = array_values($_POST);
    $insertQuery = "INSERT INTO `$table` (" . implode(", ", $fields) . ") VALUES (" . str_repeat('?, ', count($fields) - 1) . "?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param(str_repeat('s', count($values)), ...$values);
    if ($stmt->execute()) {
        $showSuccessMessage = true;
    } else {
        echo "<p>Error inserting record: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Record</title>
    <link rel="stylesheet" href="admin.css">
    <script>
        // JavaScript to hide the rest of the content after successful submission
        function handleSuccess() {
            document.getElementById('main-content').style.display = 'none';
            document.getElementById('success-message').style.display = 'block';
        }
    </script>
</head>
<body>
    <?php if ($showSuccessMessage): ?>
        <script>window.onload = handleSuccess;</script>
        <div id="success-message" style="display: none;">
            <h1>Record added successfully!</h1>
            <a href="table_view.php?table=<?= urlencode($table) ?>">Back to Table</a>
        </div>
    <?php endif; ?>
    <div id="main-content">
        <h1>Add New Record to Table: <?= htmlspecialchars($table) ?></h1>
        <form method="post">
            <?php foreach ($columns as $column): ?>
                <label for="<?= htmlspecialchars($column['Field']) ?>"><?= htmlspecialchars($column['Field']) ?></label>
                <?php
                $inputType = 'text';
                if (strpos($column['Type'], 'int') !== false) {
                    $inputType = 'number';
                } elseif (strpos($column['Type'], 'varchar') !== false || strpos($column['Type'], 'text') !== false) {
                    $inputType = 'text';
                } elseif (strpos($column['Type'], 'date') !== false) {
                    $inputType = 'date';
                }
                ?>
                <input type="<?= $inputType ?>" name="<?= htmlspecialchars($column['Field']) ?>" <?= $column['Field'] === $primaryKey ? "value=\"$newId\" readonly" : "" ?>><br>
            <?php endforeach; ?>
            <button type="submit">Add</button>
        </form>
    </div>
</body>
</html>
