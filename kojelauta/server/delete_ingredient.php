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

$owner = $data['owner'];
$oldName = $data['oldName'];
$name = $data['name'];
$quantity = (float) $data['quantity'];
$unit = $data['unit'];
$price = (float) $data['price'];

$updateResult = $collection->updateOne(
    ['owner' => $owner, 'ingredients.name' => $oldName],
    ['$set' => [
        'ingredients.$.name' => $name,
        'ingredients.$.quantity' => $quantity,
        'ingredients.$.unit' => $unit,
        'ingredients.$.price' => $price
    ]]
);

if ($updateResult->getModifiedCount() > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update ingredient.']);
}
?>
