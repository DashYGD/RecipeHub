<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);


require __DIR__ . '/../../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

if (!$db) {
    die("MongoDB connection failed");
}

// Get the user's ID from the session variable
$user_id = $_SESSION['user'];
//echo $user_id;

$collection = $db->recipes;

$searchResult = $collection->find([
    'owner' => ['$regex' => new MongoDB\BSON\Regex($user_id, 'i')]
]);

foreach ($searchResult as $i => $recipe) {
    $totalCost = 0;

    foreach ($recipe['ingredients'] as $ingredient) {
        $totalCost += $ingredient['price'];
    }

    $totalCost = number_format($totalCost, 2);

    echo '<br><div class="recipe-card" data-index="' . $i . '">';
    echo '<img src="../etusivu/' . $recipe['image'] . '" alt="' . $recipe['name'] . '">';
    echo '<h2>' . $recipe['name'] . '</h2>';
    echo '<p><strong>Category:</strong> ' . ($recipe['category'] ? $recipe['category'] : '') . '</p>';
    echo '<p> <strong>Total Cost: </strong>' . $totalCost . ' €</p>';
    echo '</div>';
}
