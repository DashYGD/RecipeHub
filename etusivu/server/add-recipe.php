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

$recipeName = $_POST['recipe-name'];
$category = $_POST['category'];
$instructions = $_POST['instructions'];
$ingredients = [];

$ingredient = $_POST['ingredient'];
$quantity = $_POST['quantity'];
$unit = $_POST['unit'];
$price = $_POST['price'];

for ($i = 0; $i < count($ingredient); $i++) {
    if (!empty($ingredient[$i]) && !empty($quantity[$i]) && !empty($unit[$i]) && !empty($price[$i])) {
        $ingredients[] = [
            'name' => $ingredient[$i],
            'quantity' => $quantity[$i],
            'unit' => $unit[$i], 
            'price' => $price[$i]
        ];
    }
}

$imageUrl = '';

$user_id = (string) $_SESSION['user_i'];

if (!empty($_FILES['image']['name'])) {
    $imageTmpName = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $newName = uniqid('', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
    $imageDes = '/var/www/RecipeHub/static/images/' . $newName;
    echo "Destination Path: " . $imageDes . "<br>";
    if (move_uploaded_file($imageTmpName, $imageDes)) {
        $imageUrl = '../static/images/' . $newName;
        echo "File moved successfully!";
    } else {
        echo "Failed to move file.";
    }
}

$collection = $db->recipes;
$result = $collection->insertOne([
    'name' => $recipeName,
    'category' => $category,
    'ingredients' => $ingredients,
    'instructions' => $instructions,
    'owner' => $user_id,
    'image' => $imageUrl
]);

if ($result->getInsertedCount() > 0) {
    echo "Recipe submitted successfully!";
} else {
    echo "Failed to submit recipe.";
}
?>
