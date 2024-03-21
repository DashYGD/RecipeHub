<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../static/server/connect.php";

// Check if user is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: ../static/server/logout');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
}

?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>adminsivu</title>
</head>
<body>
    
</body>
</html>