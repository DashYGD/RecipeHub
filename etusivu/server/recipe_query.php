<?php
session_start();

require __DIR__ . '/../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

if (!$db) {
    die("MongoDB connection failed");
}

$query = $_GET['query'];

$collection = $db->recipes;

$searchResult = $collection->find([
    'name' => ['$regex' => new MongoDB\BSON\Regex($query, 'i')]
]);

$results = iterator_to_array($searchResult);

echo json_encode($results);
