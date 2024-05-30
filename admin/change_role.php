<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $client->reseptisovellus;
$users = $db->users;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];
    $selectedRole = $_POST['role'];

    $users->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($userId)],
        ['$set' => ['is_admin' => ($selectedRole == 'Admin') ? 1 : 0]]
    );

    header("Location: /admin/?page=users");
    exit();
}
?>