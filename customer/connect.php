<?php
// Assuming you have already started the session after the login

// Include your database connection file or create a connection here
// For example, if your connection file is named "db_connection.php":
// include 'db_connection.php';

// Replace the following with your actual database connection code
$servername = "localhost";
$username = "root";
$password = "Ikram2004@";
$dbname = "gym";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming the user's contact is stored in a session variable after login
$contact = $_SESSION['user_contact'];

// Fetch user information from the database
$sql = "SELECT customer_id, c_name, birth_date, contact FROM Customer WHERE contact = '$contact'";
$result = $conn->query($sql);

// Check if there is a result
if ($result->num_rows > 0) {
    // Output data of each row
    $row = $result->fetch_assoc();
    $username = $row["c_name"];
    $user_id = $row["customer_id"];
    $birth_date = $row["birth_date"];
    $contact = $row["contact"];
} else {
    // Handle the case where no user is found with the provided contact
    echo "No user found with the provided contact.";
}

$conn->close();
?>
