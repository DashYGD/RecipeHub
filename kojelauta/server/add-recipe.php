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
$ingredients = [];

$ingredient = $_POST['ingredient'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];

for ($i = 0; $i < count($ingredient); $i++) {
    if (!empty($ingredient[$i]) && !empty($quantity[$i]) && !empty($price[$i])) {
        $ingredients[] = [
            'name' => $ingredient[$i],
            'quantity' => $quantity[$i],
            'price' => $price[$i]
        ];
    }
}

$user_id = (string) $_SESSION['user'];

$imageUrl = '';

if (!empty($_FILES['image']['name'])) {
    $imageTmpName = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $newName = uniqid('', true) . '.' . $fileName;
    $imageDes = 'C:\xampp1\htdocs\static\images\\' . $newName;
    echo "Destination Path: " . $imageDes . "<br>";
    if (move_uploaded_file($imageTmpName, $imageDes)) {
        $imageURL = '../static/images/' . $newName;
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
    'owner' => $user_id,
    'image' => $imageURL
]);

if ($result->getInsertedCount() > 0) {
    echo "Recipe submitted successfully!";
} else {
    echo "Failed to submit recipe.";
}
?>
