<?php
session_start();
require_once "dbpassword.php"; // Include your database credentials
$servername = "localhost";
$username = "root";
$dbname = "gym";
$feedbackSubmitted = false;

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
            VALUES (:f_title, :f_text, :customer_id)
        ");

        $stmt->bindParam(':f_title', $feedbackTitle);
        $stmt->bindParam(':f_text', $feedbackText);
        $stmt->bindParam(':customer_id', $customer_id);

        $stmt->execute();
        $feedbackSubmitted = true;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+Wy4HJp5iS9XnW5JLoepjj5jXpPLF3h" crossorigin="anonymous">
</head>
<style>
    
</style>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../customer_home.php">
            <img src="../img/logo-light.png" width="70" height="70" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="my_sessions.php">My sessions <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my_details.php">My details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="feedback.php">Feedback</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">Find us</a>
                </li>
                <a class="btn btn-sm btn-outline-secondary" href="log_out.php">Log out</a>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Customer Feedback Form</h2>
        <form action="feedback.php" method="post">
            <div class="form-group">
                <label for="feedbackTitle">Feedback Title:</label>
                <input type="text" class="form-control" id="feedbackTitle" name="feedbackTitle" required>
            </div>
            <div class="form-group">
                <label for="feedbackText">Feedback Text:</label>
                <textarea class="form-control" id="feedbackText" name="feedbackText" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>
    </div>

    <!-- Modal for feedback success notification -->
    <div class="modal fade" id="feedbackSuccessModal" tabindex="-1" role="dialog" aria-labelledby="feedbackSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackSuccessModalLabel">Feedback Submitted</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Thank you for your feedback! Your input has been successfully submitted.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            <?php if ($feedbackSubmitted): ?>
                $('#feedbackSuccessModal').modal('show');
            <?php endif; ?>
        });
    </script>

    <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <div class="row">
            <div class="col-12 col-md">
                <img class="mb-2" src="../img/brand.png" alt="" width="60" height="60">
                <small class="d-block mb-3 text-muted">&copy; 2019-<?php echo date("Y"); ?></small>
            </div>
            <div class="col-6 col-md">
                <h5>Features</h5>
                <ul class="list-unstyled text-small">
                    <!-- Add your feature links here -->
                </ul>
            </div>
            <div class="col-6 col-md">
                <h5>About us</h5>
                <ul class="list-unstyled text-small">
                    <!-- Add your about us links here -->
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
