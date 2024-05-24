<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $client->reseptisovellus;

// Get collection
$users = $db->users;
$users1 = $db->user_archive;

// Get user by ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user = $users->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    $users->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    $users1->insertOne($user);
}

// Redirect to users page
header('Location: /admin/?page=users');
exit();
?>