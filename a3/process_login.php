<?php
session_start();

include('includes/db_connect.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if username and password are provided
    if (!isset($_POST['username']) || !isset($_POST['password']) || 
        empty($_POST['username']) || empty($_POST['password'])) {
        $_SESSION['login_error'] = "Please enter both username and password.";
        header("Location: login.php");
        exit();
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Check database connection
    if (!$conn) {
        $_SESSION['login_error'] = "Database connection error. Please try again later.";
        header("Location: login.php");
        exit();
    }
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT userID, username, password FROM users WHERE username = ?");
    if (!$stmt) {
        $_SESSION['login_error'] = "System error: " . $conn->error;
        header("Location: login.php");
        exit();
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $hashedPassword = hash('sha256', $password);
        
        if ($hashedPassword === $user['password']) {
            // Set session variables
            $_SESSION['user_id'] = $user['userID'];
            $_SESSION['username'] = htmlspecialchars($user['username']);
            $_SESSION['logged_in'] = true;
            
            $stmt->close();
            $conn->close();
            
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid username or password.";
        }
    } else {
        $_SESSION['login_error'] = "Invalid username or password.";
    }
    
    $stmt->close();
    $conn->close();
    
    header("Location: login.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>