<?php
include('includes/db_connect.inc');

function validateInput($str) {
    return trim($str);
}

foreach ($_POST as $key => $value) {
    $$key = validateInput($value);
}

$image = null;
$upload_dir = "images/"; // Make sure this directory already exists and is writable

if (!empty($_FILES['file01']['name'])) {
    $tmp = $_FILES['file01']['tmp_name'];
    $filename = basename($_FILES['file01']['name']);
    $dest = $upload_dir . $filename;
    
    // Generate a unique filename if a file with the same name already exists
    $i = 1;
    $file_parts = pathinfo($filename);
    while (file_exists($dest)) {
        $filename = $file_parts['filename'] . "_$i." . $file_parts['extension'];
        $dest = $upload_dir . $filename;
        $i++;
    }

    if (move_uploaded_file($tmp, $dest)) {
        $image = $dest;
    } else {
        $error = error_get_last();
        echo "Failed to upload image. Error: " . $error['message'];
        echo "<br>Please contact your hosting provider to ensure the 'images' directory is writable.";
        exit();
    }
} else {
    echo "Image is required!";
    exit();
}

$sql = "INSERT INTO pets (petname, description, image, caption, age, location, type) VALUES (?,?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);

$stmt->bind_param("ssssiss", $petname, $description, $image, $caption, $age, $location, $type);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location:pets.php");
    exit(0);
} else {
    echo "An error has occurred during insertion: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>