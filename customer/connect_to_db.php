<?php
// Include the database connection file or establish a connection here
require_once "dbpassword.php"; // Change this to the actual filename if needed
$servername = "localhost";
$username = "root";
$dbname = "gym";

// Create a PDO connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>