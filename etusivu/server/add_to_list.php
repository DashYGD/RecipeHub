<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../vendor/autoload.php';

use MongoDB\Client;
use MongoDB\BSON\Regex;

// Connect to MongoDB
$mongoClient = new Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Check connection
if (!$db) {
    die("MongoDB connection failed");
}

// Check if user is logged in
if (!isset($_SESSION['user_i']) || strlen($_SESSION['user_i']) < 1) {
    echo json_encode(['status' => 'error', 'message' => 'Login first']);
    exit;
}

$collection = $db->list;
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $ingredients = $data['ingredients'];
    $owner = (string) $_SESSION['user_i'];

    // Fetch the existing ingredient list for the owner
    $existingList = $collection->findOne(['owner' => $owner]);

    if ($existingList) {
        // Combine ingredients
        $existingIngredients = $existingList['ingredients'];
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

        // Update the ingredient list with combined ingredients
        $updateResult = $collection->updateOne(
            ['owner' => new Regex($owner, 'i')],
            ['$set' => ['ingredients' => $existingIngredients]]
        );

        if ($updateResult->getModifiedCount() == 1) {
            echo json_encode(['status' => 'success', 'message' => 'Ingredients updated in list']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update ingredients in list']);
        }
    } else {
        // Insert new ingredient list for the owner
        $insertResult = $collection->insertOne(['owner' => $owner, 'ingredients' => $ingredients]);
        if ($insertResult->getInsertedCount() == 1) {
            echo json_encode(['status' => 'success', 'message' => 'Ingredients added to list']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add ingredients to list']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
}
?>
