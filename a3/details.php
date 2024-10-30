<?php
$title = "Details - Pets Victoria";
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

<main class="container-fluid my-5">
      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="col-md-12">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['caption']; ?>" class="img-fluid"/>
            </div>
            <div class="col-md-6 my-3">
                <div class="d-flex justify-content-between">
                    <div class="d-block">
                        <i class="material-icons">schedule</i>
                        <p class="description"></p><?php echo $row['age']; ?> Months</p>
                    </div>
                    <div class="d-block">
                        <i class="material-icons">pets</i>
                        <p class="description"></p><?php echo $row['type']; ?></p>
                    </div>
                    <div class="d-block">
                        <i class="material-icons">location_on</i>
                        <p class="description"></p><?php echo $row['location']; ?></p>
                    </div>
                    </div>
                    <?php 
        // Show buttons only if the logged-in user is the one who added the pet
        if (isset($_SESSION['username']) && $_SESSION['username'] == $row['username']):
        ?>
            <div class="button-group d-flex justify-content-start gap-1">
                <a href="edit.php?id=<?php echo $row['petid']; ?>" class="btn btn-primary">Edit</a>

                <!-- Button to trigger the Bootstrap modal -->
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                    Delete
                </button>
            </div>
        <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <h2 class="text-teal"><?php echo $row['petname']; ?></h2>
          <p>
          <?php echo $row['description']; ?>
          </p>
        </div>
      </div>
    </main>

    <!-- Bootstrap Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this pet?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="delete.php?id=<?php echo $row['petid']; ?>" class="btn btn-danger">Confirm</a>
            </div>
        </div>
    </div>
</div>


<?php
include('includes/footer.inc');
?>
