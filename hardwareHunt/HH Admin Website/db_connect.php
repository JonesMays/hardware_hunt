<?php
$host = 'webdev.iyaserver.com';
$user = 'nepo';
$password = 'BackendMagic1024';
$database = 'nepo_hardwareHunt2';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
