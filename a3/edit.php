<?php
$title = "Edit a Pet - Pets Victoria";
include('includes/header.inc');
include('includes/nav.inc');
include('includes/db_connect.inc');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Check if pet_id is provided in the URL
if (!isset($_GET['id'])) {
    echo "Pet ID is required.";
    exit();
}

$pet_id = $_GET['id'];

// Fetch existing pet data
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

// Check if the logged-in user is the owner of the pet
if ($pet['username'] !== $_SESSION['username']) {
    echo "You do not have permission to edit this pet.";
    exit();
}

// Initialize form variables
$pet_name = htmlspecialchars($pet['petname']);
$pet_type = htmlspecialchars($pet['type']);
$description = htmlspecialchars($pet['description']);
$caption = htmlspecialchars($pet['caption']);
$age = htmlspecialchars($pet['age']);
$location = htmlspecialchars($pet['location']);
$current_image = htmlspecialchars($pet['image']);
?>

<main>
    <div class="container-fluid my-5 mx-2">
        <h2 class="text-center text-teal">Edit a Pet</h2>
        <p class="text-center">You can edit a pet here</p>
        <div class="content">
            <form class="edit-pet-form mt-2" action="process_edit_pet.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="pet_id" value="<?php echo $pet_id; ?>">
                <div class="form-group">
                    <label for="pet-name">Pet name: <span>*</span></label>
                    <input type="text" id="pet-name" name="pet_name" value="<?php echo $pet_name; ?>" placeholder="Provide a name for the pet" class="full-width" required>
                </div>
                <div class="form-group">
                    <label for="pet-type-select">Type: <span>*</span></label>
                    <select name="pet_type" id="pet-type-select" required>
                        <option value="" disabled>Select type</option>
                        <option value="cat" <?php if ($pet_type === 'cat') echo 'selected'; ?>>Cat</option>
                        <option value="dog" <?php if ($pet_type === 'dog') echo 'selected'; ?>>Dog</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description: <span>*</span></label>
                    <textarea id="description" name="description" placeholder="Describe the pet briefly" required><?php echo $description; ?></textarea>
                </div>
                <div class="form-group choose-file">
                    <label for="image">Select an image: <span>*</span></label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <span class="max-size"><i>Max image size 500 px</i></span>
                    <p>Current Image: <img src="<?php echo $current_image; ?>" alt="Current Pet Image" width="100" /></p>
                </div>
                <div class="form-group">
                    <label for="image-caption">Image caption: <span>*</span></label>
                    <input type="text" id="image-caption" name="caption" value="<?php echo $caption; ?>" placeholder="Describe the image in one word" class="full-width" required>
                </div>
                <div class="form-group">
                    <label for="age">Age (months): <span>*</span></label>
                    <input type="number" id="age" name="age" value="<?php echo $age; ?>" placeholder="Age of the pet in months" class="full-width" required>
                </div>
                <div class="form-group">
                    <label for="location">Location: <span>*</span></label>
                    <input type="text" id="location" name="location" value="<?php echo $location; ?>" placeholder="Location of the pet" class="full-width" required>
                </div>
                <div class="button-group">
                    <button type="submit" class="submit-button">
                        <span class="material-icons">add_task</span>
                        Submit
                    </button>
                    <button type="reset" class="clear-button">
                        <span class="material-icons">close</span>
                        Clear
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php
include('includes/footer.inc');
?>
