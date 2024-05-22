<?php
session_start();

require __DIR__ . '/../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

if (!$db) {
    die("MongoDB connection failed");
}
$collection = $db->list; // "list" collection

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $recipeName = $data['name'];
    $ingredients = $data['ingredients'];
    $owner = $data['owner'];

    // Remove unnecessary fields
    unset($data['category']);
    unset($data['image']);

    // Fetch existing recipe for the same owner
    $existingRecipe = $collection->findOne(['name' => $recipeName, 'owner' => $owner]);

    if ($existingRecipe) {
        // Combine ingredients
        $existingIngredients = $existingRecipe['ingredients'];
        foreach ($ingredients as $newIngredient) {
            $found = false;
            foreach ($existingIngredients as &$existingIngredient) {
                if ($existingIngredient['name'] === $newIngredient['name'] && $existingIngredient['unit'] === $newIngredient['unit']) {
                    $existingIngredient['quantity'] += $newIngredient['quantity'];
                    $existingIngredient['price'] += $newIngredient['price'];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $existingIngredients[] = $newIngredient;
            }
        }

        // Update the recipe with combined ingredients
        $updateResult = $collection->updateOne(
            ['name' => $recipeName, 'owner' => $owner],
            ['$set' => ['ingredients' => $existingIngredients]]
        );

        if ($updateResult->getModifiedCount() == 1) {
            echo json_encode(['status' => 'success', 'message' => 'Recipe updated in basket']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update recipe in basket']);
        }
    } else {
        // Insert new recipe
        $insertResult = $collection->insertOne($data);
        if ($insertResult->getInsertedCount() == 1) {
            echo json_encode(['status' => 'success', 'message' => 'Recipe added to basket']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add recipe to basket']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
}
?>
