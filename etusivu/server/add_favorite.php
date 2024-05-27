<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../vendor/autoload.php';

use MongoDB\Client;
use MongoDB\BSON\ObjectId;

// Connect to MongoDB
$mongoClient = new Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Check connection
if (!$db) {
    die("MongoDB connection failed");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data) {
        $data['owner'] = (string) $_SESSION['user_i'];

        $collection = $db->favorites;
        $insertResult = $collection->insertOne($data);

        if ($insertResult->getInsertedCount() == 1) {
            echo json_encode(['success' => true, 'message' => 'Recipe added to favourites']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add recipe to favourites']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No data received']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
