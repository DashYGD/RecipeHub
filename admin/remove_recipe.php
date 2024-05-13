<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Select the recipes collection
$collection = $db->recipes;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    echo "Recipe ID: " . $id . " removed.";
}

// Button to go back to recipes
echo <<<HTML
<br><br>
<a href="/admin/?page=recipes">Back to recipes</a>
HTML;

exit();
?>