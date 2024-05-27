<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

if (!$db) {
    die("MongoDB connection failed");
}
$collection = $db->recipes;

$query = $_GET['query'];
$category = isset($_GET['category']) ? $_GET['category'] : '';

$regexQuery = [
    'name' => ['$regex' => new MongoDB\BSON\Regex($query, 'i')]
];

if ($category !== '') {
    $regexQuery['category'] = ['$regex' => new MongoDB\BSON\Regex($category, 'i')];
}

$searchResult = $collection->find($regexQuery);

$results = iterator_to_array($searchResult);

echo json_encode($results);
