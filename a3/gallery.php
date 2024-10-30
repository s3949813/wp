<?php
$title = "Pets Victoria - Pets";
include('includes/header.inc');
include('includes/nav.inc');
include('includes/db_connect.inc');

$sql = "SELECT petid, petname, image FROM pets";
$result = $conn->query($sql);

?>

<main class="container-fluid my-5 mx-2 text-center">
    <h2 class="text-teal my-2">Pets Victoria has a lot to offer!</h2>
    <p>
        For almost two decades, Pets Victoria has helped in creating true social
        change by bringing pet adoption into the mainstream. Our work has helped
        make a difference to the Victorian rescue community and thousands of
        pets in need of rescue and rehabilitation. But until every pet is safe,
        respected, and loved, we still have big, furry work to do.
    </p>

    <div class="row my-2">
        <div class="col-lg-12">
            <form class="mx-5">
                <input class="form-control" type="search" placeholder="Search Type" />
            </form>
        </div>
    </div>

    <div class="row my-2">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='col-sm-4 my-3'>";
                echo "<div class='image-container position-relative'>";
                echo "<a href='details.php?id=" . urlencode($row['petid']) . "'>";
                echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['petname']) . "' class='img-fluid rounded'/>";
                echo "<div class='hover-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center'>";
                echo "<i class='material-icons text-white'>search</i>";
                echo "<span class='text-white ms-2 discover-more'>DISCOVER MORE!</span>";
                echo "</div>";
                echo "</a>";
                echo "<p>" . htmlspecialchars($row['petname']) . "</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No pets available at the moment.</p>";
        }
        ?>
    </div>
</main>

<?php
include('includes/footer.inc');
?>