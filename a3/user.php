<?php
$title = "User - Pets Victoria";
include('includes/header.inc');
include('includes/nav.inc');
include('includes/db_connect.inc');

// Get the username from the URL
$username = isset($_GET['username']) ? $_GET['username'] : null;

// Fetch pets for the specific user
$sql = "SELECT * FROM pets WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

?>

<main class="container-fluid my-5">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="text-teal mb-4"><?php echo htmlspecialchars($username); ?>'s Collection</h2>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
          <div class="row">
          <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-12">
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($row['petname']); ?>" />
                        </div>
                        <div class="col-md-6 my-3">
                            <div class="d-flex justify-content-between">
                                <div class="d-block">
                                    <i class="material-icons">schedule</i>
                                    <p class="description"><?php echo htmlspecialchars($row['age']); ?> Month</p>
                                </div>
                                <div class="d-block">
                                    <i class="material-icons">pets</i>
                                    <p class="description"><?php echo htmlspecialchars($row['type']); ?></p>
                                </div>
                                <div class="d-block">
                                    <i class="material-icons">location_on</i>
                                    <p class="description"><?php echo htmlspecialchars($row['location']); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="d-flex gap-2">
                                <!-- Only show Edit and Delete buttons if the pet belongs to the logged-in user -->
                                <?php if (isset($_SESSION['username']) && $_SESSION['username'] == $row['username']): ?>
                                    <a href="edit.php?id=<?php echo $row['petid']; ?>" class="btn btn-primary">Edit</a>
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal<?php echo $row['petid']; ?>">Delete</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                        <h2 class="text-teal"><?php echo htmlspecialchars($row['petname']); ?></h2>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
          </div>

                <!-- Bootstrap Confirmation Modal for Deleting Pet -->
                <div class="modal fade" id="confirmDeleteModal<?php echo $row['petid']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete "<?php echo htmlspecialchars($row['petname']); ?>"?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <a href="delete.php?id=<?php echo $row['petid']; ?>" class="btn btn-danger">Confirm</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-lg-12">
                <p class="text-muted">You have no pets in your collection.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
include('includes/footer.inc');
?>
