<?php
$title = "Pets - Pets Victoria";
include('includes/header.inc');
include('includes/nav.inc');
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
        <div class="col-lg-3 my-3">
            <img src="images/pets.jpeg" alt="Pets" class="pets-image">
        </div>
        <div class="col-lg-3"></div>
        <div class="col-lg-6 my-3">
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
                <?php
                            include('includes/db_connect.inc');

                            $sql = "select * from pets";

                            $result = $conn->query($sql);
                            //loop through the table of results printing each row
                            if ($result->num_rows > 0) {

                                while ($row = $result->fetch_array()) {
                                    print "<tr>\n";
                                    print "<td><a href='details.php?id=" . urlencode($row['petid']) . "'>{$row['petname']}</a></td>\n";
                                    print "<td>{$row['type']}</td>\n";
                                    print "<td>{$row['age']} months</td>\n";
                                    print "<td>{$row['location']}</td>\n";
                                    print "</tr>\n";
                                }
                            } else {
                                echo "<tr><td colspan=4>0 results</td></tr>";
                            }
                            ?>
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </section>
</main>
<?php
include('includes/footer.inc');
?>