<?php
$host     = 'localhost';
$dbname   = 'returns_db';
$username = 'root';
$password = ''; // default XAMPP has no password

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
