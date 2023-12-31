<?php
session_start();
require_once "dbpassword.php"; // Include your database credentials
$servername = "localhost";
$username = "root";
$dbname = "gym";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve feedback data from the form
    $feedbackTitle = $_POST["feedbackTitle"];
    $feedbackText = $_POST["feedbackText"];
    $customer_id = $_SESSION['id'];

    try {
        // Create a PDO connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the SQL statement to insert feedback
        $stmt = $conn->prepare("
            INSERT INTO feedback (f_title, f_text, customer_id)
            VALUES (:f_title, :f_text, :customer_id,null)
        ");

        $stmt->bindParam(':f_title', $feedbackTitle);
        $stmt->bindParam(':f_text', $feedbackText);
        $stmt->bindParam(':customer_id', $customer_id);

        $stmt->execute();

        echo "Feedback submitted successfully.";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $conn = null;
}
?>
