<?php
//Check what server you are accessing
if (strstr($_SERVER['SERVER_NAME'], 'localhost')) {
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "petsvictoria";
} else {
$servername = "talsprddb02.int.its.rmit.edu.au"; // Jacob 5 server
$username = "s3949813"; // Your RMIT username
$password = "Rmit1234"; // Your RMIT MySQL password
$dbname = "s3949813"; // Your RMIT database
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}   