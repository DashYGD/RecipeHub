<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Select the users collection
$collection = $db->users;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

// Fetch the user from the collection
$user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

echo <<<HTML
<form method="post" action="edit_user.php">
    <input type="hidden" name="id" value="$id">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="$user[email]" required><br><br>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="$user[username]" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="$user[password]" required><br><br>
    <label for="is_admin">Admin:</label>
    <input type="checkbox" id="is_admin" name="is_admin" value="$user[is_admin]"><br><br>
    <input type="submit" value="Save">
</form>
HTML;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Updating user...";
}

// Redirect back to the users page with query string
header("Location: /admin/?page=users");
exit;
?>