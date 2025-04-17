<?php
$host = 'localhost';
$dbname = 'pl_deduplication';
$username = 'root'; // Change this
$password = '';     // Change this

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>