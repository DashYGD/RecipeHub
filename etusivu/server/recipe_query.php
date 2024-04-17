<?php
session_start();

// Include MongoDB library
require __DIR__ . '../../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Check connection
if (!$db) {
    die("MongoDB connection failed");
}

$query = $_GET['query'];

// Define the collection
$collection = $db->recipes;

// Perform search query
$searchResult = $collection->find([
    'otsikko' => ['$regex' => $query] // Using regular expression for partial text search
]);

// Convert search results to array
$results = iterator_to_array($searchResult);

// Convert results to JSON and output
echo json_encode($results);
