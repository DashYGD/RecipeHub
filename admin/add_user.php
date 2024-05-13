<?php

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Select the users collection
$collection = $db->users;

echo <<<HTML
<form method="post" action="add_user.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <label for="is_admin">Admin:</label>
    <input type="checkbox" id="is_admin" name="is_admin"><br><br>
    <input type="submit" value="Save">
</form>
HTML;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Adding user...";
}

// Button to go back to users
echo <<<HTML
<br><br>
<a href="/admin/?page=users">Back to users</a>
HTML;

exit;
?>