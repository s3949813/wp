<?php
include('includes/db_connect.inc');

// Initialize response array for better error handling
$response = array(
    'status' => 'error',
    'message' => '',
    'redirect' => ''
);

// Validate and sanitize input
function validateInput($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

// Validate required fields
$required_fields = ['petname', 'type', 'description', 'caption', 'age', 'location'];
$input_data = array();

foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        die("Error: {$field} is required!");
    }
    $input_data[$field] = validateInput($_POST[$field]);
}

// Image handling
$image = null;
if (!empty($_FILES['file01']['name'])) {
    $tmp = $_FILES['file01']['tmp_name'];
    $filename = basename($_FILES['file01']['name']);
    // Add timestamp to filename to prevent duplicates
    $filename = time() . '_' . $filename;
    $dest = "images/" . $filename;
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['file01']['type'], $allowed_types)) {
        die("Error: Invalid file type. Only JPG, PNG and GIF are allowed.");
    }
    
    if (move_uploaded_file($tmp, $dest)) {
        $image = $dest;
    } else {
        die("Failed to upload image.");
    }
} else {
    die("Error: Image is required!");
}

try {
    // Prepare SQL statement - removed username field since it's not in the table
    $sql = "INSERT INTO pets (petname, description, image, caption, age, location, type) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("ssssiss", 
        $input_data['petname'],
        $input_data['description'],
        $image,
        $input_data['caption'],
        $input_data['age'],
        $input_data['location'],
        $input_data['type']
    );
    
    // Execute the statement
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    // Success! Set response
    $response['status'] = 'success';
    $response['redirect'] = 'pets.php';
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    // Clean up
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}

// Handle response
if ($response['status'] === 'success') {
    header("Location: " . $response['redirect']);
    exit();
} else {
    echo "Error: " . $response['message'];
}
?>