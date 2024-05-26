<?php
session_start();

require __DIR__ . '/../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

if (!$db) {
    die("MongoDB connection failed");
}
$collection = $db->list;

$owner = $_SESSION['user_i']; // Assuming 'user' session variable stores the owner's identifier
if ($owner) {
    $searchResult = $collection->find(['owner' => new MongoDB\BSON\Regex($owner, 'i')]); // Retrieve ingredients for the specific owner
    $results = iterator_to_array($searchResult);
    echo json_encode($results);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
}
?>
