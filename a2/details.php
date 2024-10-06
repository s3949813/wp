<?php
$title = "Pets Victoria - Details";
include('includes/header.inc');
include('includes/nav.inc');
include('includes/db_connect.inc');

if (isset($_GET['id'])) {
    $pet_id = intval($_GET['id']);

    $sql = "SELECT * FROM pets WHERE petid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>Pet not found!</p>";
        exit;
    }
} else {
    echo "<p>Invalid pet ID!</p>";
    exit;
}
?>

    <main>
        <div class="details-content">
            <div class="details-image">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['caption']; ?>">
            </div>
            <div class="details">
            <div class="item">
                <i class="material-icons">schedule</i>
                <p class="description"></p><?php echo $row['age']; ?> Months</p>
            </div>
            <div class="item">
                <i class="material-icons">pets</i>
                <p class="description"></p><?php echo $row['type']; ?></p>
            </div>
            <div class="item">
                <i class="material-icons">location_on</i>
                <p class="description"></p><?php echo $row['location']; ?></p>
            </div>
            </div>
            <div class="pets-heading">
                <h2><?php echo $row['petname']; ?></h2>
                <p class="description"><?php echo $row['description']; ?></p>
            </div>
        </div>
    </main>
<?php
include('includes/footer.inc');
?>