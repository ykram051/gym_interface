<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$dbpassword="Ikram2004@";
$dbname = "gym";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming $contact and $password are user inputs (e.g., from a form)
    $contact = $_POST['contact']; // Replace with your actual input method
    $password = $_POST['r_password']; // Replace with your actual input method

    $stmt = $conn->prepare("SELECT * FROM extended_contact_table WHERE contact = :contact");
    $stmt->bindParam(':contact', $contact);
    $stmt->execute();

    // Fetch the result as an associative array
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Contact does not exist";
    } else {
        // Verify the password using password_verify
        if (password_verify($password, $user['r_password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['p_role'] = $user['p_role'];

            echo "User has the role: " . $user['p_role'];
        } else {
            echo "Invalid password";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn = null; // Close the connection in the finally block
}
?>
