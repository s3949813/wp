<?php
session_start();
include('includes/db_connect.inc');

function validateInput($str) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($str)));
}

// Initialize error array
$errors = [];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit();
}

// Validate username
if (empty($_POST['username'])) {
    $errors[] = "Username is required";
} else {
    $username = validateInput($_POST['username']);
    // Check username length
    if (strlen($username) < 3 || strlen($username) > 20) {
        $errors[] = "Username must be between 3 and 20 characters";
    }
    // Check if username already exists
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Username already exists";
    }
    $stmt->close();
}

// Validate password
if (empty($_POST['password'])) {
    $errors[] = "Password is required";
} else {
    $password = $_POST['password'];
    // Check password strength
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    if (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Password must contain at least one uppercase letter";
    }
    if (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Password must contain at least one lowercase letter";
    }
    if (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must contain at least one number";
    }
}

// Validate password confirmation
if (empty($_POST['confirm_password'])) {
    $errors[] = "Please confirm your password";
} elseif ($_POST['password'] !== $_POST['confirm_password']) {
    $errors[] = "Passwords do not match";
}

// Validate email
if (empty($_POST['email'])) {
    $errors[] = "Email is required";
} else {
    $email = validateInput($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    // Check if email already exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Email already registered";
    }
    $stmt->close();
}

// If there are errors, redirect back to registration page
if (!empty($errors)) {
    $_SESSION['registration_errors'] = $errors;
    $_SESSION['prev_username'] = $username ?? '';
    $_SESSION['prev_email'] = $email ?? '';
    header("Location: register.php");
    exit();
}

// If we get here, validation passed - proceed with registration
try {
    // Hash password
    $hashedPassword = hash('sha256', $password);
    
    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, created_at) VALUES (?, ?, ?, NOW())");
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("sss", $username, $hashedPassword, $email);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    // Registration successful
    $_SESSION['success_message'] = "Registration successful! Please log in.";
    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();
    
} catch (Exception $e) {
    $_SESSION['registration_errors'] = ["An error occurred during registration. Please try again later."];
    error_log("Registration error: " . $e->getMessage());
    header("Location: register.php");
    exit();
}
?>