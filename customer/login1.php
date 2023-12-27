<?php
session_start();
include "connect_to_db.php";

function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['contact']) && isset($_POST['r_password'])) {
    $contact = validate($_POST['contact']);
    $password = validate($_POST['r_password']);

    if (empty($contact)) {
        header("Location: index.php?error=User contact is required");
        exit();
    } elseif (empty($password)) {
        header("Location: index.php?error=Password is required");
        exit();
    }

    $sql = "SELECT * FROM extended_contact_table WHERE contact = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $contact, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Fetch additional information from the customer table
        $customerSql = "SELECT c_name, customer_id, birth_date FROM customer WHERE contact = ?";
        $customerStmt = $conn->prepare($customerSql);
        $customerStmt->bind_param('s', $contact);
        $customerStmt->execute();
        $customerResult = $customerStmt->get_result();

        if ($customerResult->num_rows > 0) {
            $customerData = $customerResult->fetch_assoc();

            // Set additional session variables
            $_SESSION['username'] = $customerData['c_name'];
            $_SESSION['user_id'] = $customerData['customer_id'];
            $_SESSION['birth_date'] = $customerData['birth_date'];
        }

        $customerStmt->close();

        $_SESSION['contact'] = $row['contact'];
        $_SESSION['password'] = $row['password'];

        if ($row['p_role'] == 'admin') {
            header('Location: admin_home.php');
        } elseif ($row['p_role'] == 'customer') {
            header('Location: customer_home.php');
        } else {
            // Default redirection
            header('Location: home.php');
        }
        exit();
    } else {
        // Invalid password
        header("Location: index.php?error=Invalid password");
        exit();
    }
} else {
    // Handle the case when contact or password is not set
    header("Location: index.php?error=Invalid request");
    exit();
}
?>
