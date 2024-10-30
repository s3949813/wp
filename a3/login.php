<?php
$title = "Login - Pets Victoria";
include('includes/header.inc');
include('includes/nav.inc');
?>

<main class="container-fluid">
    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center text-teal">Login</h2>

                <!-- Display login error message -->
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger">
                        <?php
                            echo $_SESSION['login_error'];
                            unset($_SESSION['login_error']);  // Clear the error message after displaying
                        ?>
                    </div>
                <?php endif; ?>

                <form class="bg-light p-4 shadow rounded" action="process_login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" id="username" placeholder="Enter your username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn bg-teal text-white w-100">Login</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php
include('includes/footer.inc');
?>
