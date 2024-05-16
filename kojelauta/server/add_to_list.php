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
    $insertResult = $collection->insertOne($data);
    if ($insertResult->getInsertedCount() == 1) {
        echo json_encode(['status' => 'success', 'message' => 'Recipe added to basket']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add recipe to basket']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
}
?>
