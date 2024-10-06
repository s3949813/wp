<?php
$title = "Pets Victoria - Pets";
include('includes/header.inc');
include('includes/nav.inc');
include('includes/db_connect.inc');

$sql = "SELECT petid, petname, image FROM pets";
$result = $conn->query($sql);

?>

<main>
    <h2>Pets Victoria has a lot to offer!</h2>
    <p class="description">For almost two decades, Pets Victoria has helped in creating true social change by bringing pet adoption into the mainstream. Our work has helped make a difference to the Victorian rescue community and thousands of pets in need of rescue and rehabilitation, but, until every pet is safe, respected, and loved, we all still have big, hairy work to do.</p>
    <div class="container">
        <div class="row">
        <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    echo "<div class='image-container'>";
                    echo "<a href='details.php?id=" . urlencode($row['petid']) . "'>";
                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['petname']) . "'>";
                    echo "<div class='hover-overlay'>";
                    echo "<i class='fa fa-search'></i>";
                    echo "<span class='discover-more'>DISCOVER MORE!</span>";
                    echo "</div>";
                    echo "</a>";
                    echo "<p>" . htmlspecialchars($row['petname']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No pets available at the moment.</p>";
            }
            ?>
        </div>
    </div>
</main>

<?php
include('includes/footer.inc');
?>