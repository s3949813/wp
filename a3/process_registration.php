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
    $email = trim(mysqli_real_escape_string($conn, $_POST['email'])); // Added email sanitization

    // Validate input
    if (empty($username) || empty($password) || empty($email)) { // Check if email is empty
        die("Username, password, or email cannot be empty.");
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
        die("Username already exists.");
    }

    // Insert new user into the database
    $insert_sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);

    if ($insert_stmt === false) {
        die("Error preparing insert statement: " . $conn->error);
    }

    // Bind parameters and execute
    $insert_stmt->bind_param('sss', $username, $password, $email); // Added email binding
    if (!$insert_stmt->execute()) {
        die("Error executing insert statement: " . $insert_stmt->error);
    }

    echo "Registration successful!";
}
?>
