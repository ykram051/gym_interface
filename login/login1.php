<?php
require_once "dbpassword.php";
$servername = "localhost";
$username = "root";
//$password = "";
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

    // get the result
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$result) {
        echo "Contact does not exist";
    } else {
        if ($password == $result[0]['r_password']) {
            echo "User has the role: " . $result[0]['p_role'];
        } else {
            echo "Invalid password";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
