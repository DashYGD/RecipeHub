<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $client->reseptisovellus;

// Select the recipes collection
$recipes = $db->recipe_archive;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $recipes->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
}

// Redirect to recipes page
header('Location: /admin/?page=archive');
exit();
?>