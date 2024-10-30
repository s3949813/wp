<?php
$title = "Register - Pets Victoria";
include('includes/header.inc');
include('includes/nav.inc');
?>

<main class="container-fluid">
        <section class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="text-center text-teal">Register</h2>
                    <form class="bg-light p-4 shadow rounded" action="process_registration.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Choose a username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Create a password" required>
                        </div>
                        <button type="submit" class="btn bg-teal text-white w-100">Register</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

<?php
include('includes/footer.inc');
?>