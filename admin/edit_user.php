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
} else {
    echo "User not found";
    exit;
}

// Fetch the user from the collection
$user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

echo <<<HTML
<form method="post" action="edit_user.php?id=$id">
    <input type="hidden" name="id" value="$id">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="$user[email]" required><br><br>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="$user[username]" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="$user[user_password]" required><br><br>
    <label for="is_admin">Admin:</label>
    <select id="is_admin" name="is_admin">
        <option value="1">Yes</option>
        <option value="0">No</option>
    </select><br><br>
    <input type="submit" value="Save">
</form>
HTML;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userUpdated = [
        'email' => $_POST['email'],
        'username' => $_POST['username'],
        'user_password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'is_admin' => (isset($_POST['is_admin']) ? 1 : 0),
    ];
    $result = $collection->updateOne(['_id' => new MongoDB\BSON\ObjectId($id)], ['$set' => $userUpdated]);
    if ($result->getModifiedCount() === 0) {
        echo "Error updating user: " . $result->getErrorMessage();
    } else {
        echo "User updated: " . $user['username'];
    }
}

// Button to go back to users
echo <<<HTML
<br><br>
<a href="/admin/?page=users">Back to users</a>
HTML;
exit;
?>