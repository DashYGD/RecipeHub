<?php

// Include MongoDB library
require '../vendor/autoload.php';

$client = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $client->reseptisovellus;

// Select the recipe archive collection
$collection = $db->recipe_archive;

// Remove recipe
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $recipe = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
}

// Redirect to recipes page
header('Location: /admin/?page=recipes');
exit();
?>