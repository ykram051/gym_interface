<?php
session_start();

require_once "dbpassword.php";
$servername = "localhost";
$username = "root";
$dbname = "gym";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $contact = $_POST['contact'];
    $password = $_POST['r_password'];

    $stmt = $conn->prepare("SELECT * FROM extended_contact_table WHERE contact = :contact");
    $stmt->bindParam(':contact', $contact);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$result) {
        // Delay response to help prevent username enumeration attacks
        sleep(2);
        echo "Invalid username or password";
    } else {
        if (password_verify($password, $result[0]['r_password'])) {
            $_SESSION['user_id'] = $result[0]['user_id'];
            $_SESSION['username'] = $result[0]['username'];
            header('Location: welcome.php'); // Redirect to the welcome page
            exit();
        } else {
            echo "Invalid username or password";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
