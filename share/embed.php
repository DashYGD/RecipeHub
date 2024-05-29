<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

// Connect to MongoDB
$mongoClient = new Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Check connection
if (!$db) {
    die("MongoDB connection failed");
}

$recipesCollection = $db->list;

if (isset($_GET['id'])) {
    $recipeId = $_GET['id'];

    $recipe = $recipesCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($recipeId)]);


    if ($recipe) {
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>Embedded Shopping List</title>';
        echo '<style>';
        echo 'body {';
        echo '    font-family: Arial, sans-serif;';
        echo '    margin: 0;';
        echo '    padding: 0;';
        echo '}';
        echo 'ul {';
        echo '    list-style-type: none;';
        echo '    padding: 0;';
        echo '}';
        echo 'li {';
        echo '    display: flex;';
        echo '    align-items: center; /* Align items vertically */';
        echo '    font-size: 16px;';
        echo '    margin-bottom: 10px;';
        echo '}';
        echo 'li span {';
        echo '    flex: 1; /* Equal width for all spans */';
        echo '}';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        echo '<h2>Ostoslista</h2>';
        echo '<ul>';
        foreach ($recipe['ingredients'] as $ingredient) {
            echo '<li>';
            echo '<span>' . $ingredient['name'] . '</span>';
            echo '<span>' . $ingredient['quantity'] . ' ' . $ingredient['unit'] . '</span>';
            echo '<span>' . $ingredient['price'] . '€</span>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</body>';
        echo '</html>';
    } else {
        echo 'Recipe not found';
    }
    
}
?>
