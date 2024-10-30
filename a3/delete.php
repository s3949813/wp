<?php
session_start();
include('includes/db_connect.inc');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $pet_id = intval($_GET['id']);

    // Fetch pet details to get the image path and owner info
    $sql = "SELECT image, username FROM pets WHERE petid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    $stmt->bind_result($image, $owner);
    $stmt->fetch();
    $stmt->close();

    // Ensure the logged-in user is the owner of the pet
    if ($_SESSION['username'] !== $owner) {
        echo "You are not authorized to delete this pet.";
        exit();
    }

    // Delete the image file if it exists
    if (file_exists($image)) {
        unlink($image);
    }

    // Delete the pet record from the database
    $sql = "DELETE FROM pets WHERE petid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pet_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: pets.php");
        exit();
    } else {
        echo "Failed to delete the pet.";
    }
} else {
    echo "Invalid request.";
}
?>
