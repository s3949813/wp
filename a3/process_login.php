<?php
session_start();

include('includes/db_connect.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Use full SHA256 hash instead of truncated version
    $encryptedPassword = hash('sha256', $password);
    
    // Improved SQL query with proper error handling
    $stmt = $conn->prepare("SELECT userID, username FROM users WHERE username = ? AND password = ?");
    if (!$stmt) {
        $_SESSION['login_error'] = "System error, please try again later.";
        header("Location: login.php");
        exit();
    }
    
    $stmt->bind_param("ss", $username, $encryptedPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['userID'];
        $_SESSION['username'] = htmlspecialchars($user['username']);
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
} else {
    header("Location: login.php");
    exit();
}
?>