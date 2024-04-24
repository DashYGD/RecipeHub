<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$name = $_GET['name'];
$category = $_GET['category'];
$ingredients = $_GET['ingredients'];
$instructions = $_GET['instructions'];
$author = $_GET['author'];

echo <<<HTML
<form method="get" action="edit_recipe.php">
    <input type="hidden" name="id" value="$id">
    <input type="text" name="category" value="$category">
    <input type="text" name="name" value="$name">
    <input type="text" name="ingredients" value="$ingredients">
    <input type="text" name="instructions" value="$instructions">
    <input type="text" name="author" value="$author">
    <input type="submit" value="Save">
</form>
HTML;

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Select the recipes collection
$collection = $db->recipes;

// Update in Database
$collection->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($id)],
    ['$set' => [
        'name' => $name,
        'description' => $description,
        'ingredients' => $ingredients,
        'instructions' => $instructions,
        'author' => $author
    ]]
);

// Redirect to the recipes page
header('Location: recipes.php');
exit;
?>