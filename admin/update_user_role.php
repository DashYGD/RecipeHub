<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$users = $client->users;

// Update user role
if (isset($_GET['id']) && isset($_GET['role'])) {
    $id = $_GET['id'];
    $role = $_GET['role'];
    $users->updateOne(['_id' => new MongoDB\BSON\ObjectId($id)], ['$set' => ['role' => $role]]);
}

// Redirect back to users page
header('Location: users.php');
exit();
?>