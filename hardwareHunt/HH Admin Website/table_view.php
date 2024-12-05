<?php
require_once 'db_connect.php';
include 'admin_navbar.php';
$table = $_GET['table'] ?? '';

if (!$table) {
    die("Table not specified.");
}

// Fetch all records from the specified table
$query = "SELECT * FROM `$table`";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching data: " . $conn->error);
}

$columns = $result->fetch_fields();

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

// Handle delete action
$showSuccessMessage = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $deleteQuery = "DELETE FROM `$table` WHERE `$primaryKey` = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param('i', $deleteId);
    if ($stmt->execute()) {
        $showSuccessMessage = true;
    } else {
        echo "<p>Error deleting record: " . $stmt->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View <?= htmlspecialchars($table) ?></title>
    <link rel="stylesheet" href="admin.css">
    <script>
        function handleSuccess() {
            document.getElementById('main-content').style.display = 'none';
            document.getElementById('success-message').style.display = 'block';
        }
        function confirmDelete(id) {
            return confirm(`Are you sure you want to delete the record with ID ${id}?`);
        }
    </script>
</head>
<body>
    <?php if ($showSuccessMessage): ?>
        <script>window.onload = handleSuccess;</script>
        <div id="success-message" style="display: none;">
            <h1>Record deleted successfully!</h1>
            <a href="table_view.php?table=<?= urlencode($table) ?>">Back to Table</a>
        </div>
    <?php endif; ?>
    <div id="main-content">
        <h1>Viewing Records in Table: <?= htmlspecialchars($table) ?></h1>
        <a href="add_record.php?table=<?= urlencode($table) ?>" class="action-button add-button">Add New Record</a>
        <table>
            <thead>
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <th><?= htmlspecialchars($column->name) ?></th>
                    <?php endforeach; ?>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <?php foreach ($columns as $column): ?>
                            <td>
                                <?php if (stripos($column->name, 'image') !== false && !empty($row[$column->name])): ?>
                                    <img src="<?= htmlspecialchars($row[$column->name]) ?>" alt="Image" style="max-width: 100px;">
                                <?php else: ?>
                                    <?= htmlspecialchars($row[$column->name]) ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                        <td>
                            <a href="edit_record.php?table=<?= urlencode($table) ?>&id=<?= urlencode($row[$primaryKey]) ?>" class="action-button edit-button">Edit</a>
                        </td>
                        <td>
                            <form method="post" style="display:inline;" onsubmit="return confirmDelete(<?= htmlspecialchars($row[$primaryKey]) ?>);">
                                <input type="hidden" name="delete_id" value="<?= htmlspecialchars($row[$primaryKey]) ?>">
                                <button type="submit" class="action-button delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
