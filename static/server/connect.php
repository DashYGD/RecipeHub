<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the MongoDB PHP library
require 'vendor/autoload.php';

// Connect to MongoDB
try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    echo "Connected successfully";
} catch (MongoDB\Driver\Exception\ConnectionException $e) {
    die("MongoDB connection failed: " . $e->getMessage());
}

// Select database
$db = $mongoClient->reseptisovellus;

// Check if the database exists
if (!$db) {
    die("Database selection failed");
}

// Close MongoDB connection (optional)
//$mongoClient->close();

