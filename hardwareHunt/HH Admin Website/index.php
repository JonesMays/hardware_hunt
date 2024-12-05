<?php
// index.php
include 'admin_navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hardware Hunt Database</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Welcome to the Hardware Hunt Database</h1>
    <ul>
        <li><a href="table_view.php?table=components">View Components</a></li>
        <li><a href="table_view.php?table=componentType">View Component Types</a></li>
        <li><a href="table_view.php?table=manufacturers">View Manufacturers</a></li>
        <li><a href="table_view.php?table=projects">View Projects</a></li>
        <li><a href="table_view.php?table=reviews">View Reviews</a></li>
        <li><a href="table_view.php?table=users">View Users</a></li>
    </ul>
</body>
</html>
