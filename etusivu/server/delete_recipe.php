<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../vendor/autoload.php';

use MongoDB\BSON\ObjectId;

$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

if (!$db) {
    die("MongoDB connection failed");
}

$collection = $db->recipes;

$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['recipe_id'])) {
    $recipeIdString = $data['recipe_id'];

    try {
        $recipeId = new ObjectId($recipeIdString);
    } catch (InvalidArgumentException $e) {
        echo json_encode(['success' => false, 'message' => 'Invalid recipe ID format']);
        exit;
    }

    $deleteResult = $collection->deleteOne(['_id' => $recipeId]);

    if ($deleteResult->getDeletedCount() == 1) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Recipe not found or error occurred']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request or recipe ID missing']);
}
?>
