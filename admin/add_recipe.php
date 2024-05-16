<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Select the recipes collection
$collection = $db->recipes;
$users = $db->users;

echo <<<HTML
<form method="post" action="add_recipe.php">
    <label for="category">Category:</label>
    <input type="text" id="category" name="category" required><br><br>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>
    <label for="ingredients">Ingredients:</label>
    <div id="ingredients-container">
        <div class="ingredient">
            <input type="text" name="ingredient_name[]" placeholder="Name" required>
            <input type="number" name="ingredient_quantity[]" placeholder="Quantity" required>
            <input type="number" name="ingredient_price[]" placeholder="Price" required>
            <button type="button" class="remove-ingredient-button">Remove</button>
        </div>
    </div><br><br>
    <label for="add-ingredient-button">Add Ingredient:</label>
    <button type="button" id="add-ingredient-button">Add Ingredient</button><br><br>
    <label for="instructions">Instructions:</label>
    <textarea id="instructions" name="instructions" required></textarea><br><br>
    <label for="author">Author:</label>
    <input type="text" id="author" name="author" required><br><br>
    <input type="submit" value="Save">
</form>

<script>
    document.getElementById("add-ingredient-button").addEventListener("click", function() {
        const ingredientContainer = document.getElementById("ingredients-container");
        const newIngredient = document.createElement("div");
        newIngredient.classList.add("ingredient");
        newIngredient.innerHTML = `
            <input type="text" name="ingredient_name[]" placeholder="Name">
            <input type="number" name="ingredient_quantity[]" placeholder="Quantity">
            <input type="number" name="ingredient_price[]" placeholder="Price">
            <button type="button" class="remove-ingredient-button">Remove</button>
        `;
        ingredientContainer.appendChild(newIngredient);
    });
    document.getElementById("ingredients-container").addEventListener("click", function(event) {
        if (event.target.classList.contains("remove-ingredient-button")) {
            event.target.parentElement.remove();
        }
    });
</script>
HTML;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $name = $_POST['name'];
    
    // Extract ingredients array of objects
    $ingredients = [];
    $ingredientCount = is_array($_POST['ingredient_name']) ? count($_POST['ingredient_name']) : 0;
    for ($i = 0; $i < $ingredientCount; $i++) {
        $ingredient = [
            'name' => $_POST['ingredient_name'][$i],
            'quantity' => $_POST['ingredient_quantity'][$i],
            'price' => $_POST['ingredient_price'][$i]
        ];
        array_push($ingredients, $ingredient);
    }

    $instructions = $_POST['instructions'];
    $author = $_POST['author'];

    // Get author ID using author name
    $authorId = $users->findOne(['username' => $author])['_id'];
    $owner = (string) $authorId;

    // Insert recipe into MongoDB collection
    $recipeData = [
        'category' => $category,
        'name' => $name,
        'ingredients' => $ingredients,
        'instructions' => $instructions,
        'owner' => $owner
    ];

    $insertResult = $collection->insertOne($recipeData);

    if ($insertResult->getInsertedCount() === 1) {
        echo "Recipe inserted successfully!";
    } else {
        echo "Failed to insert recipe.";
    }
}

// Button to go back to recipes
echo <<<HTML
<br><br>
<a href="/admin/?page=recipes">Back to recipes</a>
HTML;

exit;
?>