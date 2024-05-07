<?php
session_start();

require __DIR__ . '/../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

if (!$db) {
    die("MongoDB connection failed");
}
$collection = $db->recipes;

$query = $_GET['query'];
$category = isset($_GET['category']) ? $_GET['category'] : ''; // Check if category is set, otherwise use empty string

$regexQuery = [
    'name' => ['$regex' => new MongoDB\BSON\Regex($query, 'i')]
];

if ($category !== '') { // Check if a category is provided
    $regexQuery['category'] = ['$regex' => new MongoDB\BSON\Regex($category, 'i')];
}

$searchResult = $collection->find($regexQuery);

$results = iterator_to_array($searchResult);

echo json_encode($results);
