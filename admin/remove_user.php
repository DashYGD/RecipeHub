<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Select the users collection
$collection = $db->users;

// Remove user for demonstration purposes
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    echo "User ID: " . $id . " removed.";
}

// Button to go back to users
echo <<<HTML
<br><br>
<a href="/admin/?page=users">Back to users</a>
HTML;

exit();
?>