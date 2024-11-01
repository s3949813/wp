<?php
session_start(); // Start the session
include('includes/db_connect.inc');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

function validateInput($str) {
    return htmlspecialchars(trim($str)); // Sanitize input
}

foreach ($_POST as $key => $value) {
    $$key = validateInput($value);
}

$image = null;

// Validate file upload
if (!empty($_FILES['file01']['name'])) {
    $tmp = $_FILES['file01']['tmp_name'];
    $dest = "images/" . basename($_FILES['file01']['name']);

    // Move uploaded file
    if (move_uploaded_file($tmp, $dest)) {
        $image = $dest;
    } else {
        echo "Failed to upload image.";
        exit();
    }
} else {
    echo "Image is required!";
    exit();
}

// Prepare SQL statement to prevent SQL injection
$sql = "INSERT INTO pets (petname, description, image, caption, age, location, type, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Get the user ID from the session (assuming it's stored in session)
$username = $_SESSION['username'];

// Bind parameters (s = string, i = integer)
$stmt->bind_param("ssssisss", $petname, $description, $image, $caption, $age, $location, $type, $username);

// Execute the statement
if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: pets.php");
    exit(0);
} else {
    echo "An error has occurred during insertion!";
}

$stmt->close();
$conn->close();
?>
