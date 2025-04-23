<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'pl_deduplication');

if ($conn->connect_error) {
    $_SESSION['register_error'] = 'Database connection failed.';
    header('Location: register.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['register_error'] = 'All fields are required.';
        header('Location: register.php');
        exit();
    }

    // Check if username or email already exists
    $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['register_error'] = 'Username or email already exists.';
        header('Location: register.php');
        exit();
    }
    $stmt->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $username, $email, $hashed_password);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        unset($_SESSION['attempted_email']);
        header('Location: index.php');
    }
    else {
        $_SESSION['register_error'] = 'Registration failed. Please try again.';
        header('Location: register.php');
    }
    $stmt->close();
}
$conn->close();
?>