<?php
session_start();
include('includes/db_connect.inc');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Not logged in. Please log in first.");
}

// Debug database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Debug session variables
echo "Debug - Session variables:<br>";
echo "user_id: " . $_SESSION['user_id'] . "<br>";
echo "username: " . $_SESSION['username'] . "<br>";

function validateInput($str) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($str)));
}

// Debug POST data
echo "Debug - POST data:<br>";
print_r($_POST);
echo "<br>";

// Debug FILES data
echo "Debug - FILES data:<br>";
print_r($_FILES);
echo "<br>";

// Validate and collect form data
$petname = isset($_POST['petname']) ? validateInput($_POST['petname']) : die("Pet name is required");
$description = isset($_POST['description']) ? validateInput($_POST['description']) : die("Description is required");
$caption = isset($_POST['caption']) ? validateInput($_POST['caption']) : die("Caption is required");
$age = isset($_POST['age']) ? filter_var($_POST['age'], FILTER_VALIDATE_FLOAT) : die("Age is required");
$location = isset($_POST['location']) ? validateInput($_POST['location']) : die("Location is required");
$type = isset($_POST['type']) ? validateInput($_POST['type']) : die("Type is required");
$username = isset($_SESSION['username']) ? validateInput($_SESSION['username']) : die("Username not found in session");

// Handle file upload
if (!empty($_FILES['file01']['name'])) {
    $tmp = $_FILES['file01']['tmp_name'];
    $dest = "images/" . basename($_FILES['file01']['name']);

    // Check if images directory exists and is writable
    if (!is_dir("images/")) {
        die("Images directory does not exist");
    }
    if (!is_writable("images/")) {
        die("Images directory is not writable");
    }

    // Move uploaded file
    if (move_uploaded_file($tmp, $dest)) {
        $image = $dest;
        echo "Debug - File uploaded successfully to: " . $dest . "<br>";
    } else {
        die("Failed to upload image. Error: " . error_get_last()['message']);
    }
} else {
    die("Image is required!");
}

// Debug values before SQL
echo "Debug - Values to be inserted:<br>";
echo "petname: $petname<br>";
echo "description: $description<br>";
echo "image: $image<br>";
echo "caption: $caption<br>";
echo "age: $age<br>";
echo "location: $location<br>";
echo "type: $type<br>";
echo "username: $username<br>";

// Check if pets table exists
$table_check = $conn->query("SHOW TABLES LIKE 'pets'");
if ($table_check->num_rows == 0) {
    die("Pets table does not exist in database");
}

// Show table structure
$structure = $conn->query("DESCRIBE pets");
echo "Debug - Table structure:<br>";
while ($row = $structure->fetch_assoc()) {
    print_r($row);
    echo "<br>";
}

// Prepare SQL statement
$sql = "INSERT INTO pets (petname, description, image, caption, age, location, type, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
echo "Debug - SQL query: " . $sql . "<br>";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error . 
        "<br>Error number: " . $conn->errno .
        "<br>SQL State: " . $conn->sqlstate);
}

// Bind parameters using double for age
if (!$stmt->bind_param("ssssdss", 
    $petname, 
    $description, 
    $image, 
    $caption, 
    $age, 
    $location, 
    $type, 
    $username
)) {
    die("Binding parameters failed: " . $stmt->error);
}

// Execute the statement
if ($stmt->execute()) {
    echo "Debug - Insert successful. Last insert ID: " . $conn->insert_id . "<br>";
    $stmt->close();
    $conn->close();
    header("Location: pets.php");
    exit(0);
} else {
    die("Execute failed: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>