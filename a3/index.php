<?php
$title = "Home - Pets Victoria";
include('includes/header.inc');
include('includes/nav.inc');
include('includes/db_connect.inc');

// Fetch the last 4 images from the 'pets' table
$query = "SELECT image, caption FROM pets ORDER BY petid DESC LIMIT 4";
$result = $conn->query($query);
$images = $result->fetch_all(MYSQLI_ASSOC); // Fetch data as an associative array
?>
<main class="container-fluid">
    <!-- Display success message if available -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                echo htmlspecialchars($_SESSION['message']); 
                unset($_SESSION['message']);  // Clear the message after displaying
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Display login error if any -->
    <?php if (isset($_SESSION['login_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
                echo htmlspecialchars($_SESSION['login_error']); 
                unset($_SESSION['login_error']);  // Clear error after displaying
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <section id="hero">
    <div class="container pt-2 pb-4">
        <div class="row pt-2 pb-5">
        <!-- Carousel on the left -->
        <div class="col-sm-6 my-5">
            <div
            id="carouselExampleIndicators"
            class="carousel slide"
            data-bs-ride="carousel"
            >
            <div class="carousel-indicators">
            <?php foreach ($images as $index => $image): ?>
                                <button type="button" data-bs-target="#carouselExampleIndicators" 
                                    data-bs-slide-to="<?php echo $index; ?>" 
                                    class="<?php echo $index === 0 ? 'active' : ''; ?>" 
                                    aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>" 
                                    aria-label="Slide <?php echo $index + 1; ?>"></button>
                            <?php endforeach; ?>
            </div>
            <div class="carousel-inner">
            <?php foreach ($images as $index => $image): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="<?php echo htmlspecialchars($image['image']); ?>" 
                                         class="d-block w-100" 
                                         style="max-width: 500px; max-height: 500px;" 
                                         alt="<?php echo htmlspecialchars($image['caption']); ?>">
                                </div>
                            <?php endforeach; ?>
            </div>
            <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev"
            >
                <span
                class="carousel-control-prev-icon"
                aria-hidden="true"
                ></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button
                class="carousel-control-next"
                type="button"
                data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next"
            >
                <span
                class="carousel-control-next-icon"
                aria-hidden="true"
                ></span>
                <span class="visually-hidden">Next</span>
            </button>
            </div>
        </div>
        <!-- Hero content on the right -->
        <div class="col-sm-6 d-flex align-items-center">
            <div class="text">
            <h1>PETS VICTORIA</h1>
            <p>WELCOME TO PET ADOPTION</p>
            </div>
        </div>
        </div>
    </div>
    </section>
    <section id="content">
    <div class="container mb-5">
        <form class="d-md-flex" action="search.php" method="GET">
            <input
                class="form-control me-2 w-75 my-1"
                type="search"
                name="keyword"
                placeholder="I am looking for ..."
            >
            <select class="form-control me-2 w-25 my-1" name="pet_type">
                <option value="">--Select Pet Type--</option>
                <option value="cat">Cat</option>
                <option value="dog">Dog</option>
            </select>
            <button class="btn btn-primary bg-teal my-1" type="submit">Search</button>
        </form>

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
    </div>
    </section>
</main>
<?php
include('includes/footer.inc');
?>