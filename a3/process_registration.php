<?php
session_start();
include('includes/db_connect.inc');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function validateInput($str) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($str)));
}

// Validate all required fields are present
$required_fields = ['petname', 'description', 'caption', 'age', 'location', 'type'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        die("Error: $field is required!");
    }
}

// Sanitize input
$petname = validateInput($_POST['petname']);
$description = validateInput($_POST['description']);
$caption = validateInput($_POST['caption']);
$age = filter_var($_POST['age'], FILTER_VALIDATE_FLOAT); // Changed to FLOAT to match double in DB
$location = validateInput($_POST['location']);
$type = validateInput($_POST['type']);
$username = $_SESSION['username'];

// Validate age
if ($age === false || $age < 0) {
    die("Error: Please enter a valid age!");
}

// Handle file upload
if (!isset($_FILES['file01']) || $_FILES['file01']['error'] !== UPLOAD_ERR_OK) {
    die("Error: " . ($_FILES['file01']['error'] ?? "Image upload is required!"));
}

// Validate file type
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$file_info = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($file_info, $_FILES['file01']['tmp_name']);
finfo_close($file_info);

if (!in_array($mime_type, $allowed_types)) {
    die("Error: Invalid file type. Only JPG, PNG, and GIF files are allowed.");
}

// Create images directory if it doesn't exist
$upload_dir = "images/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Generate unique filename
$file_extension = pathinfo($_FILES['file01']['name'], PATHINFO_EXTENSION);
$unique_filename = uniqid('pet_', true) . '.' . $file_extension;
$destination = $upload_dir . $unique_filename;

// Move uploaded file
if (!move_uploaded_file($_FILES['file01']['tmp_name'], $destination)) {
    die("Error: Failed to upload image. Please try again.");
}

// First, check if the username exists in users table
$check_user_sql = "SELECT userID FROM users WHERE username = ?";
$check_stmt = $conn->prepare($check_user_sql);

if ($check_stmt === false) {
    unlink($destination);
    die("Error preparing user check statement: " . $conn->error);
}

$check_stmt->bind_param("s", $username);

if (!$check_stmt->execute()) {
    unlink($destination);
    $error = "Error checking username: " . $check_stmt->error;
    $check_stmt->close();
    die($error);
}

$check_stmt->store_result();
if ($check_stmt->num_rows === 0) {
    unlink($destination);
    $check_stmt->close();
    die("Error: Invalid username");
}
$check_stmt->close();

// Prepare SQL statement for pet insertion
$sql = "INSERT INTO pets (petname, description, image, caption, age, location, type, username) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    unlink($destination);
    die("Error preparing statement: " . $conn->error . "\nSQL: " . $sql);
}

// Debug: Print the SQL and parameters
echo "SQL: " . $sql . "\n";
echo "Parameters: " . $petname . ", " . $description . ", " . $destination . ", " . 
     $caption . ", " . $age . ", " . $location . ", " . $type . ", " . $username . "\n";

// Bind parameters - using 'd' for double (age)
if (!$stmt->bind_param("ssssdsss", 
    $petname, 
    $description, 
    $destination, 
    $caption, 
    $age, 
    $location, 
    $type, 
    $username
)) {
    unlink($destination);
    die("Error binding parameters: " . $stmt->error);
}

// Execute the statement
if (!$stmt->execute()) {
    unlink($destination);
    $error = "Error executing statement: " . $stmt->error;
    $stmt->close();
    $conn->close();
    die($error);
}

// Success
$stmt->close();
$conn->close();
header("Location: pets.php");
exit();
?>