<?php
// Remove user for demonstration purposes
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    echo "User ID: " . $id . " removed.";
}

// Redirect back to the users page with query string
header("Location: /admin/?page=users");

exit();

// Remove user from database
// require_once 'connect.php';

// Prepare and execute the SQL statement
// if ($stmt = $conn->prepare("DELETE FROM users WHERE id = ?")) {
//     $stmt->bind_param("i", $id);
//     $id = $_GET['id'];
//     $stmt->execute();
//     $stmt->close();
// }

// Close the database connection
// $conn->close();
?>