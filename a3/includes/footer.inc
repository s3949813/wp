<footer class="bg-orange text-teal text-center py-3">
    <div class="user-links">
        <?php
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Only show user links if user is logged in
        if (isset($_SESSION['username'])) {
            // Database connection
            include('includes/db_connect.inc');

            // Check if connection is successful
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch unique usernames of users who uploaded pets
            $sql = "SELECT DISTINCT users.username 
                    FROM users 
                    JOIN pets ON users.username = pets.username";

            // Add error handling for query
            $result = $conn->query($sql);

            if ($result === false) {
                echo "Error executing query: " . $conn->error;
            } else {
                if ($result->num_rows > 0) {
                    // Display each username as a hyperlink
                    while ($row = $result->fetch_assoc()) {
                        $username = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); // Escape for security
                        echo "<a href='user.php?username=$username' class='mx-2'>$username</a>";
                    }
                } else {
                    echo "<p>No users have uploaded pets yet.</p>";
                }
            }

            // Close the database connection
            $conn->close();
        } else {
            // Optional: Show a message or login link for non-logged in users
            echo "<p>Please <a href='login.php'>login</a> to see user profiles</p>";
        }
        ?>
    </div>
    <p>&copy; <?php echo date('Y'); ?> S3949813 Reyan Hussain | All Rights Reserved | Designed for Pets Victoria</p>
</footer>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
