<?php
include('includes/db_connect.inc');

function validateInput($str)
{
    return trim($str);
}

foreach ($_POST as $key => $value) {
    $$key = validateInput($value);
}

$image = null;

if (!empty($_FILES['file01']['name'])) {
    $tmp = $_FILES['file01']['tmp_name'];
    $dest = "images/" . basename($_FILES['file01']['name']);

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

$sql = "INSERT INTO pets (petname, description, image, caption, age, location, type) VALUES (?,?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);

$stmt->bind_param("ssssiss", $petname, $description, $image, $caption, $age, $location, $type);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location:pets.php");
    exit(0);
} else {
    echo "An error has occurred during insertion!";
}

$stmt->close();
$conn->close();
?>
