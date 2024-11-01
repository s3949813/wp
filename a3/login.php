<?php
session_start();
$title = "Login - Pets Victoria";

// If user is already logged in, redirect to index
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit();
}

include('includes/header.inc');
include('includes/nav.inc');
?>

<main class="container-fluid">
    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center text-teal mb-4">Login to Pets Victoria</h2>

                <!-- Display login error message -->
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php 
                            echo $_SESSION['login_error'];
                            unset($_SESSION['login_error']); // Clear the error message
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Display success message (e.g., after registration) -->
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php 
                            echo $_SESSION['success_message'];
                            unset($_SESSION['success_message']); 
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form class="bg-light p-4 shadow rounded" action="process_login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" id="username" 
                               placeholder="Enter your username" required
                               value="<?php echo isset($_SESSION['prev_username']) ? htmlspecialchars($_SESSION['prev_username']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="password" 
                                   placeholder="Enter your password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-teal text-white w-100 mb-3">Login</button>
                    <div class="text-center">
                        <p class="mb-0">Don't have an account? <a href="register.php" class="text-teal">Register here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<!-- Add JavaScript for password toggle -->
<script>
document.getElementById('togglePassword').addEventListener('click', function (e) {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    
    // Toggle password visibility
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});
</script>

<?php
include('includes/footer.inc');
?>