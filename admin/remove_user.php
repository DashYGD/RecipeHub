<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $client->reseptisovellus;

// Select the users collection
$users = $db->users;

// Remove user
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $users->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
}

// Redirect to users page
header('Location: /admin/?page=users');
exit();
?>