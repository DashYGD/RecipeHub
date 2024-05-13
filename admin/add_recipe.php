<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Select the recipes collection
$collection = $db->recipes;

echo <<<HTML
<form method="post" action="add_recipe.php">
    <label for="category">Category:</label>
    <input type="text" id="category" name="category" required><br><br>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>
    <label for="ingredients">Ingredients:</label>
    <textarea id="ingredients" name="ingredients" required></textarea><br><br>
    <label for="instructions">Instructions:</label>
    <textarea id="instructions" name="instructions" required></textarea><br><br>
    <input type="submit" value="Save">
</form>
HTML;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Adding recipe...";
}

// Button to go back to recipes
echo <<<HTML
<br><br>
<a href="/admin/?page=recipes">Back to recipes</a>
HTML;

exit;
?>