<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $client->reseptisovellus;

// Get collection
$recipes = $db->recipes;
$recipes1 = $db->recipe_archive;

// Get recipe by ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $recipe = $recipes->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    $recipes->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    $recipes1->insertOne($recipe);
}

// Redirect to recipes page
header('Location: /admin/?page=recipes');
exit();
?>