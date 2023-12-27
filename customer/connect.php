<?php
// Start the session
session_start();

// Include your database connection file
require_once "dbpassword.php";
$servername = "localhost";
$username = "root";
$dbname = "gym";

try {
    // Create a database connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get user input
    $contact = $_POST['contact'];
    $password = $_POST['r_password'];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT * FROM extended_contact_table WHERE contact = :contact");
    $stmt->bindParam(':contact', $contact);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if the result is empty
    if (!$result) {
        // Delay response to help prevent username enumeration attacks
        sleep(2);
        echo "Invalid username or password";
    } else {
        // Verify the password
        if (password_verify($password, $result[0]['r_password'])) {
            // Store user information in the session
            $_SESSION['user_id'] = $result[0]['user_id'];
            $_SESSION['username'] = $result[0]['username'];
            
            // Redirect to the welcome page
            header('Location: welcome.php');
            exit();
        } else {
            echo "Invalid username or password";
        }
    }
} catch (PDOException $e) {
    // Log the error (don't display detailed error messages in a production environment)
    error_log("Error: " . $e->getMessage());
    echo "An error occurred. Please try again later.";
}

// Close the database connection
$conn = null;
?>
