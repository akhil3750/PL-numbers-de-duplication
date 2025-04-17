<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pl_deduplication";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pl_number = $conn->real_escape_string($_POST['pl_number']);
    $description = $conn->real_escape_string($_POST['description']);
    
    // Check for duplicate
    $check = $conn->query("SELECT COUNT(*) as count FROM pl_numbers WHERE pl_number = '$pl_number'");
    $row = $check->fetch_assoc();
    $is_duplicate = $row['count'] > 0 ? 1 : 0;

    $sql = "INSERT INTO pl_numbers (pl_number, description, is_duplicate) VALUES ('$pl_number', '$description', $is_duplicate)";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>