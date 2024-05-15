<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Select the users collection
$collection = $db->users;

// Remove user
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
}

// Redirect to users page
header('Location: /admin/?page=users');

exit();
?>