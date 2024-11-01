<?php 
session_start(); 
$title = "Register"; 
include('includes/header.inc'); 
include('includes/nav.inc'); 
include('includes/db_connect.inc'); 
 
// Enable error reporting 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $username = htmlspecialchars($_POST['username']); 
    $password = htmlspecialchars($_POST['password']); 
    $confirm_password = htmlspecialchars($_POST['confirm_password']); 
 
    if ($password !== $confirm_password) { 
        echo "Passwords do not match."; 
    } else { 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
 
        // Prepare the SQL statement 
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)"); 
         
        // Check if the prepare() method was successful 
        if ($stmt === false) { 
            die("Error preparing the SQL statement: " . $conn->error); 
        } 
 
        $stmt->bind_param("ss", $username, $hashed_password); 
 
        if ($stmt->execute()) { 
            echo "Registration successful. <a href='login.php'>Login here</a>."; 
        } else { 
            echo "Error: " . $stmt->error; 
        } 
 
        $stmt->close(); 
        $conn->close(); 
    } 
} 
?> 
 
<main class="container"> 
    <h2>Register</h2> 
    <form action="register.php" method="post"> 
        <div class="form-group"> 
            <label for="username">Username: <span>*</span></label> 
            <input type="text" id="username" name="username" class="form-control" required> 
        </div> 
        <div class="form-group"> 
            <label for="password">Password: <span>*</span></label> 
            <input type="password" id="password" name="password" class="form-control" required> 
        </div> 
        <div class="form-group"> 
            <label for="confirm_password">Confirm Password: <span>*</span></label> 
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required> 
        </div> 
        <button type="submit" class="btn btn-primary">Register</button> 
    </form> 
</main> 
 
<?php include('includes/footer.inc'); ?>