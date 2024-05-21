<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Select the recipes collection
$collection = $db->recipes;
$collection1 = $db->recipe_archive;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $recipe = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    $collection1->insertOne($recipe);
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
}

// Redirect to recipes page
header('Location: /admin/?page=recipes');
exit();
?>