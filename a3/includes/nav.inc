<?php
session_start(); // Start the session
?>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-teal">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.jpeg" alt="Pets Victoria Logo" class="logo-img">
            </a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <!-- Common links for all users -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pets.php">Pets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Links for logged-in users -->
                        <li class="nav-item">
                            <a class="nav-link" href="add.php">Add More</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <!-- Links for guests (not logged in) -->
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>

                </ul>

                <form class="d-flex" action="search.php" method="GET">
                    <input 
                        class="form-control me-2" 
                        type="search" 
                        name="keyword" 
                        placeholder="Search" 
                        required
                    >
                    <button class="btn btn-transparent text-white" type="submit">
                        <span class="material-icons">search</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>
</header>
