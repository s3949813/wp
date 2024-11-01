<?php
session_start();  // Start session

include('includes/db_connect.inc');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $encryptedPassword = substr(hash('sha256', $password), 0, 40);

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT userID, username FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $encryptedPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['userID'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['message'] = "Login successful.";

        header("Location: index.php"); // Redirect to home
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid username or password.";
        header("Location: login.php"); // Redirect back to login
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>
