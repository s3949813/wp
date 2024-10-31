<footer class="bg-orange text-teal text-center py-3">
    <div class="user-links">
        <?php
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Database connection
        include('includes/db_connect.inc');

        // Check if connection is successful
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        try {
            // Use prepared statement to prevent SQL injection
            $sql = "SELECT DISTINCT users.username 
                    FROM users 
                    JOIN pets ON users.username = pets.username 
                    ORDER BY users.username";

            // Add error handling for query
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                throw new Exception("Error preparing statement: " . $conn->error);
            }

            if (!$stmt->execute()) {
                throw new Exception("Error executing query: " . $stmt->error);
            }

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Display each username as a hyperlink
                while ($row = $result->fetch_assoc()) {
                    $username = htmlspecialchars($row['username']); // Escape for security
                    echo "<a href='user.php?username=$username' class='mx-2 text-decoration-none'>$username</a>";
                }
            } else {
                echo "<p class='mb-0'>No users found.</p>";
            }

            $stmt->close();
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo "<p class='mb-0'>Error loading user links.</p>";
        }

        $conn->close();
        ?>
    </div>
    <p class="mt-2 mb-0">
        &copy; <?php echo date('Y'); ?> 
        <?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])): ?>
            <?php echo htmlspecialchars($_SESSION['username']); ?>
        <?php else: ?>
            S3949813 REYAN HUSSAIN
        <?php endif; ?>
        . All Rights Reserved | Designed for Pets Victoria
    </p>
</footer>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>