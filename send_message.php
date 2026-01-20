<?php
// show errors (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// database connection
$conn = new mysqli("localhost", "root", "", "coffeeshop");
if ($conn->connect_error) {
    die("Database connection failed");
}

// check request
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // get form data
    $name    = trim($_POST["name"]);
    $email   = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    // simple validation
    if ($name && $email && $subject && $message) {

        // insert message
        $stmt = $conn->prepare(
            "INSERT INTO messages (name, email, subject, message)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        $stmt->execute();
        $stmt->close();

        // redirect back to contact page
        header("Location: contactus.php?sent=1");
        exit;
    }
}

$conn->close();
