<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "dbpassword.php";
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$dbname = "gym";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming $contact and $password are user inputs (e.g., from a form)
    $contact = $_POST['contact']; // Replace with your actual input method
    $password = $_POST['r_password']; // Replace with your actual input method

    // Validate inputs (you might want to add more validation based on your requirements)
    if (empty($contact) || empty($password)) {
        echo "Please provide both contact and password";
    } else {
        $stmt = $conn->prepare("SELECT * FROM extended_contact_table WHERE contact = :contact");
        $stmt->bindParam(':contact', $contact);
        $stmt->execute();

        // Fetch the result as an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            echo "Contact does not exist";
        } else {
            // Compare the provided password with the stored plain text password
            if ($password == $result['r_password']) {
                echo "Login successful. User has the role: " . $result['p_role'];

            $customerSql = "SELECT ec.contact, ec.p_role, c.c_name, c.customer_id, c.birth_date
              FROM extended_contact_table ec
              JOIN customer c ON ec.contact = c.contact
              WHERE ec.contact = :contact";


            $customerStmt = $conn->prepare($customerSql);
            $customerStmt->bindParam(':contact', $contact);
            $customerStmt->execute();

         $customerResult= $customerStmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$customerResult) {
                    echo "Error fetching customer information";
                } else {
                    // Set additional session variables
                    $_SESSION['username'] = $customerResult['c_name'];
                    $_SESSION['user_id'] = $customerResult['customer_id'];
                    $_SESSION['birth_date'] = $customerResult['birth_date'];

                    header("Location: customer_home.php");
                    exit();
                }
            } else {
                echo "Invalid password";
            }
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    // Close the database connection
    $conn = null;
}
?>
