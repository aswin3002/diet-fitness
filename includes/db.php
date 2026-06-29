<?php
$conn = new mysqli('localhost', 'root', '', 'diet_fitness');
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>