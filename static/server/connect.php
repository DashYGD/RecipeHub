<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require '../../vendor/autoload.php';

try {
    $mongoClient = new MongoDB\Client("mongodb://65.21.248.139:27017/");
    echo "Connected successfully";
} catch (MongoDB\Driver\Exception\ConnectionException $e) {
    die("MongoDB connection failed: " . $e->getMessage());
}

$db = $mongoClient->reseptisovellus;

if (!$db) {
    die("Database selection failed");
}

//$mongoClient->close();

