<?php
include('includes/db_connect.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check database connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Collect and sanitize input
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($password)) {
        die("Username or password cannot be empty.");
    }

    // Check if username already exists
    $check_sql = "SELECT userID FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    
    if ($check_stmt === false) {
        die("Error preparing check statement: " . $conn->error);
    }
    
    $check_stmt->bind_param('s', $username);
    
    if (!$check_stmt->execute()) {
        die("Error executing check statement: " . $check_stmt->error);
    }
    
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $check_stmt->close();
        die("Username is already taken. Please try a different one.");
    }
    
    $check_stmt->close();

    // Hash password
    $encrypted_password = substr(hash('sha256', $password), 0, 40);

    // Insert new user
    $insert_sql = "INSERT INTO users (username, password, reg_date) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($insert_sql);
    
    if ($stmt === false) {
        die("Error preparing insert statement: " . $conn->error . 
            "\nSQL: " . $insert_sql);
    }

    $stmt->bind_param('ss', $username, $encrypted_password);

    if ($stmt->execute()) {
        echo "Registration successful! Redirecting to the login page...";
        $stmt->close();
        $conn->close();
        header('refresh:3; url=login.php');
        exit;
    } else {
        $error = "Error executing insert statement: " . $stmt->error;
        $stmt->close();
        die($error);
    }
} else {
    die("Invalid request method.");
}
?>