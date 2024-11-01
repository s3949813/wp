<?php
$title = "Pets - Pets Victoria";
include('includes/header.inc');
include('includes/nav.inc');

// Database connection
include('includes/db_connect.inc');

// Check if 'petid' is set and retrieve the pet details
if (isset($_GET['petid'])) {
    $petid = $_GET['petid'];

    // Query to fetch pet details from the database
    $sql = "SELECT * FROM pets WHERE petID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $petid); // Bind the petid as an integer

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Fetch the pet details
            $pet = $result->fetch_assoc();
            // Display the pet details
            echo "<h3>" . htmlspecialchars($pet['petname']) . "</h3>";
            echo "<p>" . htmlspecialchars($pet['description']) . "</p>";
            if (!empty($pet['image'])) {
                echo "<img src='" . htmlspecialchars($pet['image']) . "' alt='Pet Image' class='img-fluid' />";
            }
            echo "<p><strong>Caption:</strong> " . htmlspecialchars($pet['caption']) . "</p>";
            echo "<p><strong>Age:</strong> " . htmlspecialchars($pet['age']) . "</p>";
            echo "<p><strong>Location:</strong> " . htmlspecialchars($pet['location']) . "</p>";
            echo "<p><strong>Type:</strong> " . htmlspecialchars($pet['type']) . "</p>";
        } else {
            echo "<p class='text-danger'>No pet found with the provided ID.</p>";
        }
    } else {
        echo "<p class='text-danger'>Error executing query: " . htmlspecialchars($conn->error) . "</p>";
    }

    $stmt->close();
} else {
    echo "<p class='text-danger'>Error: Pet ID is not provided.</p>";
}

$conn->close();
?>

<main class="container-fluid">
  <section id="content">
    <div class="container-fluid my-5 text-center">
      <h2>Discover Pets Victoria</h2>
      <p>
        Pets Victoria is a dedicated pet adoption organization based in
        Victoria, Australia, focused on providing a safe and loving
        environment for pets in need. With a compassionate approach, Pets
        Victoria works tirelessly to rescue, rehabilitate, and rehome dogs,
        cats, and other animals. Their mission is to connect these deserving
        pets with caring individuals and families, creating lifelong bonds.
        The organization offers a range of services, including adoption
        counseling, pet education, and community support programs, all aimed
        at promoting responsible pet ownership and reducing the number of
        homeless animals.
      </p>
      <div class="row mt-5">
        <!-- Pet details will be displayed here -->
      </div>
    </div>
  </section>
</main>

<?php
include('includes/footer.inc');
?>
