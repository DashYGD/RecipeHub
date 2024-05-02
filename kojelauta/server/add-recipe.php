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

// Retrieve form data
$recipeName = $_POST['recipe-name'];
$category = $_POST['category'];
$ingredients = []; // Initialize an array to store ingredients

// Parse ingredients from form data
$ingredient = $_POST['ingredient'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];

// Loop through each ingredient
for ($i = 0; $i < count($ingredient); $i++) {
    // Check if all fields are provided for this ingredient
    if (!empty($ingredient[$i]) && !empty($quantity[$i]) && !empty($price[$i])) {
        // Add the ingredient to the array
        $ingredients[] = [
            'name' => $ingredient[$i],
            'quantity' => $quantity[$i],
            'price' => $price[$i]
        ];
    }
}

// Get the user's ID from the session variable
$user_id = (string) $_SESSION['user'];

// Handle image upload separately and store the image URL
// Assuming $_FILES['image'] contains the uploaded image file
// Handle image upload separately and store the image URL
// Assuming $_FILES['image'] contains the uploaded image file
$imageUrl = ''; // Placeholder for the image URL

if (!empty($_FILES['image']['name'])) {
    $imageTmpName = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $newName = uniqid('', true) . '.' . $fileName;
    $imageDes = 'C:\xampp1\htdocs\static\images\\' . $newName; // Adjust the destination path as needed
    echo "Destination Path: " . $imageDes . "<br>";
    if (move_uploaded_file($imageTmpName, $imageDes)) {
        $imageURL = '../static/images/' . $newName;
        echo "File moved successfully!";
    } else {
        echo "Failed to move file.";
    }
}


// Insert recipe data into MongoDB
$collection = $db->recipes;
$result = $collection->insertOne([
    'name' => $recipeName,
    'category' => $category,
    'ingredients' => $ingredients,
    'owner' => $user_id, // Add user ID to the "owner" column
    'image' => $imageURL // Store the image URL in the "image" column
]);

if ($result->getInsertedCount() > 0) {
    echo "Recipe submitted successfully!";
} else {
    echo "Failed to submit recipe.";
}
?>
