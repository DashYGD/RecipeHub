<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../vendor/autoload.php';

use MongoDB\Client;
use MongoDB\BSON\ObjectId;

// Connect to MongoDB
$mongoClient = new Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Check connection
if (!$db) {
    die("MongoDB connection failed");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['user_i'] == '') {
        echo json_encode(['success' => false, 'message' => 'Login first']);
        exit;
    }

    $recipeId = $_POST['recipeId'];
    $updatedRecipe = json_decode($_POST['recipe'], true);
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $newName = uniqid('', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $imageDes = '/var/www/RecipeHub/static/images/' . $newName;

        if (!is_writable(dirname($imageDes))) {
            die("Directory is not writable: " . dirname($imageDes));
        }

        if (move_uploaded_file($imageTmpName, $imageDes)) {
            $updatedRecipe['image'] = '../static/images/' . $newName;
        } else {
            $error = error_get_last();
            die("Failed to move file. Error: " . $error['message']);
        }
    }

    $collection = $db->recipes;
    $updateResult = $collection->updateOne(
        ['_id' => new ObjectId($recipeId)],
        ['$set' => $updatedRecipe]
    );

    if ($updateResult->getModifiedCount() == 1) {
        echo json_encode(['success' => true, 'message' => 'Recipe updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update recipe']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
