<?php
session_start();

include('includes/db_connect.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize user input
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = trim($_POST['password']);

    // Hash the password using SHA-256
    $encryptedPassword = hash('sha256', $password);

    // Prepare the SQL query to select the user
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
        // Invalid credentials
        $_SESSION['login_error'] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }
} else {
    // Invalid request method
    $_SESSION['login_error'] = "Invalid request method.";
    header("Location: login.php");
    exit();
}
?>
