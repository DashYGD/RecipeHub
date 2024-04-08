<?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

echo <<<HTML
<form method="post" action="edit_user.php">
    <input type="hidden" name="id" value="$id">
    <input type="email" name="email" value="$_POST[email]">
    <input type="text" name="username" value="$_POST[username]">
    <input type="password" name="password" value="$_POST[password]">
    <input type="text" name="is_admin" value="$_POST[is_admin]">
    <input type="submit" value="Save">
</form>
HTML;

// Update in database

// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Select the users collection
$collection = $db->users;

// Prepare and execute the SQL statement
if ($stmt = $collection->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($id)],
    ['$set' => [
        'email' => $_POST['email'],
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'is_admin' => $_POST['is_admin']
    ]]
)) {
    echo "User updated successfully.";
} else {
    echo "Error updating user: " . $conn->error;
}

// Redirect back to the users page with query string
header("Location: /admin/?page=users");


?>