<?php
require_once 'db_connect.php';
include 'admin_navbar.php';
$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';

// Validate table and ID
if (!$table || !$id) {
    die("Table or ID not specified.");
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

// Fetch the record to edit
$query = "SELECT * FROM `$table` WHERE `$primaryKey` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Fetch column information for the table
$columnsQuery = "DESCRIBE `$table`";
$columnsResult = $conn->query($columnsQuery);

if (!$columnsResult) {
    die("Error fetching columns: " . $conn->error);
}

$columns = [];
while ($colRow = $columnsResult->fetch_assoc()) {
    $columns[] = $colRow;
}

// Success message trigger
$showSuccessMessage = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = array_keys($row);
    $placeholders = [];
    $values = [];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $placeholders[] = "$field = ?";
            $values[] = $_POST[$field];
        }
    }

    $updateQuery = "UPDATE `$table` SET " . implode(", ", $placeholders) . " WHERE `$primaryKey` = ?";
    $values[] = $id;

    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param(str_repeat('s', count($values) - 1) . 'i', ...$values);

    if ($stmt->execute()) {
        $showSuccessMessage = true;
    } else {
        echo "<p>Error updating record: " . $stmt->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Record</title>
    <link rel="stylesheet" href="admin.css">
    <script>
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
            <h1>Record updated successfully!</h1>
            <a href="table_view.php?table=<?= urlencode($table) ?>">Back to Table</a>
        </div>
    <?php endif; ?>
    <div id="main-content">
        <h1>Edit Record in Table: <?= htmlspecialchars($table) ?></h1>
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
                <input type="<?= $inputType ?>" name="<?= htmlspecialchars($column['Field']) ?>" value="<?= htmlspecialchars($row[$column['Field']]) ?>"><br>
            <?php endforeach; ?>
            <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>
