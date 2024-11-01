<?php 
session_start(); 
$title = "Login"; 
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
 
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?"); 
    $stmt->bind_param("s", $username); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
 
    if ($result->num_rows > 0) { 
        $row = $result->fetch_assoc(); 
        if (password_verify($password, $row['password'])) { 
            $_SESSION['loggedin'] = true; 
            $_SESSION['username'] = $username; 
            header('Location: index.php'); 
            exit; 
        } else { 
            echo "Invalid password."; 
        } 
    } else { 
        echo "No user found with that username."; 
    } 
 
    $stmt->close(); 
    $conn->close(); 
} 
?> 
 
<main class="container"> 
    <h2>Login</h2> 
    <form action="login.php" method="post"> 
        <div class="form-group"> 
            <label for="username">Username: <span>*</span></label> 
            <input type="text" id="username" name="username" class="form-control" required> 
        </div> 
        <div class="form-group"> 
            <label for="password">Password: <span>*</span></label> 
            <input type="password" id="password" name="password" class="form-control" required> 
        </div> 
        <button type="submit" class="btn btn-primary">Login</button> 
    </form> 
</main> 
 
<?php include('includes/footer.inc'); ?>