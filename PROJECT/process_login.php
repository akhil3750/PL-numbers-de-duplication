<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'pl_deduplication');

if ($conn->connect_error) {
    $_SESSION['login_error'] = 'Database connection failed.';
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'All fields are required.';
        header('Location: login.php');
        exit();
    }

    // Check if user exists
    $stmt = $conn->prepare('SELECT id, username, password FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            unset($_SESSION['attempted_email']);
            header('Location: index.php');
        } else {
            $_SESSION['login_error'] = 'Invalid password.';
            header('Location: login.php');
        }
    } else {
        $_SESSION['register_prompt'] = 'No account found with that email. Please register.';
        $_SESSION['attempted_email'] = $email;
        header('Location: register.php');
    }
    $stmt->close();
}
$conn->close();
?>