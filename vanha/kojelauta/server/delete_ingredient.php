<?php
session_start();

require __DIR__ . '/../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

if (!$db) {
    die("MongoDB connection failed");
}
$collection = $db->list;

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $owner = $data['owner'];
    $ingredientName = $data['name'];

    $recipe = $collection->findOne(['owner' => $owner]);

if ($recipe) {
    $ingredients = iterator_to_array($recipe['ingredients']); // Convert BSONArray to PHP array
    $updatedIngredients = array_filter($ingredients, function($ingredient) use ($ingredientName) {
        return $ingredient['name'] !== $ingredientName;
    });

        $updateResult = $collection->updateOne(
            ['owner' => $owner],
            ['$set' => ['ingredients' => array_values($updatedIngredients)]]
        );

        if ($updateResult->getModifiedCount() == 1) {
            echo json_encode(['status' => 'success', 'message' => 'Ingredient deleted']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete ingredient']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Recipe not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
}
?>
