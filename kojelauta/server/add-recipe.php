<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

if (!$db) {
    die("MongoDB connection failed");
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $recipeName = $_POST['recipe-name'];
    $category = $_POST['category'];
    $ingredients = []; // Initialize an array to store ingredients

    // Parse ingredients from form data
    $ingredient = $_POST['ingredient'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Loop through each ingredient
    for ($i = 0; $i < count($ingredient); $i++) {
        // Check if all fields are provided for this ingredient
        if (!empty($ingredient[$i]) && !empty($quantity[$i]) && !empty($price[$i])) {
            // Add the ingredient to the array
            $ingredients[] = [
                'name' => $ingredient[$i],
                'quantity' => $quantity[$i],
                'price' => $price[$i]
            ];
        }
    }

    // Get the user's ID from the session variable
    $user_id = $_SESSION['user'];

    // Insert recipe data into MongoDB
    $collection = $db->recipes;
    $result = $collection->insertOne([
        'owner' => $user_id, // Add user ID to the "owner" column
        'recipe_name' => $recipeName,
        'category' => $category,
        'ingredients' => $ingredients,
        // You may want to handle image upload separately and store the image URL here
        'image_url' => '' // Placeholder for image URL
    ]);

    if ($result->getInsertedCount() > 0) {
        echo "Recipe submitted successfully!";
    } else {
        echo "Failed to submit recipe.";
    }
}
?>
