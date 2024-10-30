<?php
session_start();
include('includes/db_connect.inc');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the pet ID from POST data
if (!isset($_POST['pet_id'])) {
    echo "Pet ID is required.";
    exit();
}

$pet_id = $_POST['pet_id'];
$pet_name = $_POST['pet_name'];
$pet_type = $_POST['pet_type'];
$description = $_POST['description'];
$caption = $_POST['caption'];
$age = $_POST['age'];
$location = $_POST['location'];

// Fetch current pet data
$sql = "SELECT * FROM pets WHERE petid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Pet not found.";
    exit();
}

$pet = $result->fetch_assoc();
$current_image = $pet['image'];

// Handle image upload
if (!empty($_FILES['image']['name'])) {
    // Delete the old image file if it exists
    if (file_exists($current_image)) {
        unlink($current_image);
    }

    // Handle new image upload
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    $new_image = $target_file;
} else {
    // If no new image is uploaded, keep the current image
    $new_image = $current_image;
}

// Update pet data
$sql = "UPDATE pets SET petname = ?, type = ?, description = ?, image = ?, caption = ?, age = ?, location = ? WHERE petid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssisi", $pet_name, $pet_type, $description, $new_image, $caption, $age, $location, $pet_id);
$stmt->execute();

// Redirect back to the user's collection or a success page
header("Location: user.php?username=" . urlencode($_SESSION['username'])); // Assuming username is stored in session
exit();
?>
