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
}

// Fetch the recipe from the collection
$recipe = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

$ingredientsString = '';
foreach ($recipe['ingredients'] as $ingredient) {
    $ingredientsString .= $ingredient['name'] . "\n";
}

echo <<<HTML
<form method="post" action="edit_recipe.php">
    <input type="hidden" name="id" value="$id">
    <label for="category">Category:</label>
    <input type="text" id="category" name="category" value="$recipe[category]" required><br><br>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="$recipe[name]" required><br><br>
    <label for="ingredients">Ingredients:</label>
    <textarea id="ingredients" name="ingredients" required>$ingredientsString</textarea><br><br>
    <label for="instructions">Instructions:</label>
    <textarea id="instructions" name="instructions" required>$recipe[instructions]</textarea><br><br>
    <input type="submit" value="Save">
</form>
HTML;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipeUpdated = [
        'category' => $_POST['category'],
        'name' => $_POST['name'],
        'ingredients' => [
            'name' => $_POST['ingredients'],
        ],
        'instructions' => $_POST['instructions'],
    ];
    $result = $collection->updateOne(['_id' => new MongoDB\BSON\ObjectId($id)], ['$set' => $recipeUpdated]);
    if ($result->getModifiedCount() === 0) {
        echo "Error updating recipe: " . $result->getErrorMessage();
    } else {
        echo "Recipe updated: " . $recipe['name'];
    }
}

// Button to go back to recipes
echo <<<HTML
<br><br>
<a href="/admin/?page=recipes">Back to recipes</a>
HTML;

exit;
?>