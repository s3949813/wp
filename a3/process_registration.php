<?php
include('includes/db_connect.inc'); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "Username or password cannot be empty.";
        exit;
    }

    // Check if username already exists
    $check_stmt = $conn->prepare("SELECT userID FROM users WHERE username = ?");
    $check_stmt->bind_param('s', $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "Username is already taken. Please try a different one.";
        $check_stmt->close();
        $conn->close();
        exit;
    }
    $check_stmt->close();

    $encrypted_password = substr(hash('sha256', $password), 0, 40);

    // Prepare SQL statement to insert the new user
    $stmt = $conn->prepare("INSERT INTO users (username, password, reg_date) VALUES (?, ?, NOW())");

    if ($stmt) {
        $stmt->bind_param('ss', $username, $encrypted_password);

        // Execute the statement and handle errors
        if ($stmt->execute()) {
            echo "Registration successful! Redirecting to the login page...";
            header('refresh:3; url=login.php');
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
