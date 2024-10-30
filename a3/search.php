<?php
$title = "Search - Pets Victoria";
include('includes/header.inc');
include('includes/nav.inc');
include('includes/db_connect.inc');

// Initialize search variables
$keyword = $pet_type = "";
$results = [];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";
    $pet_type = isset($_GET['pet_type']) ? trim($_GET['pet_type']) : "";

    // Build the SQL query with placeholders for prepared statements
    $sql = "SELECT petid, petname, type, age, location FROM pets WHERE 1=1";

    // Add conditions dynamically
    $params = [];
    $types = ''; // For bind_param

    if (!empty($keyword)) {
        $sql .= " AND (petname LIKE ? OR description LIKE ?)";
        $params[] = "%{$keyword}%";
        $params[] = "%{$keyword}%";
        $types .= 'ss';
    }

    if (!empty($pet_type)) {
        $sql .= " AND type = ?";
        $params[] = $pet_type;
        $types .= 's';
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters dynamically
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }

    // Execute the query and fetch results
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    $stmt->close();
    $conn->close();
}
?>

<main class="container-fluid">
    <div class="container-fluid my-5">
        <h2 class="text-center text-teal">Search Pets</h2>

        <form method="GET" class="d-md-flex">
            <input 
                class="form-control me-2 w-75 my-1" 
                type="search" 
                name="keyword" 
                placeholder="I am looking for ..." 
                value="<?= htmlspecialchars($keyword) ?>"
            >
            <select class="form-control me-2 w-25 my-1" name="pet_type">
                <option value="">--Select Pet Type--</option>
                <option value="cat" <?= $pet_type == 'cat' ? 'selected' : '' ?>>Cat</option>
                <option value="dog" <?= $pet_type == 'dog' ? 'selected' : '' ?>>Dog</option>
            </select>
            <button class="btn btn-primary bg-teal my-1" type="submit">Search</button>
        </form>

        <div class="row mt-5">
            <div class="col-lg-12 my-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Pet</th>
                            <th scope="col">Type</th>
                            <th scope="col">Age</th>
                            <th scope="col">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($results): ?>
                            <?php foreach ($results as $pet): ?>
                                <tr>
                                    <td>
                                        <a href="details.php?id=<?= urlencode((string) $pet['petid']) ?>">
                                        <?= htmlspecialchars($pet['petname']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($pet['type']) ?></td>
                                    <td><?= htmlspecialchars($pet['age']) ?> months</td>
                                    <td><?= htmlspecialchars($pet['location']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No pets found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.inc'); ?>
