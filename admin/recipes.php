<?php
// Include MongoDB library
require '../vendor/autoload.php';

// Connect to MongoDB
try {
    $mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
    $db = $mongoClient->reseptisovellus;
} catch (MongoDB\Driver\Exception\Exception $e) {
    // Handle connection errors
    die("Error connecting to MongoDB: " . $e->getMessage());
}

// Select the recipes collection
$collection = $db->recipes;
$collection1 = $db->users;

$recipe_archive = $db->recipe_archive;

// Fetch all documents from the collection
$recipes = $collection->find();
$recipe_archives = $recipe_archive->find();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .hidden { display: none; }
        .cursor-pointer { cursor: pointer; }
        .ingredients-container {
            overflow: hidden;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Content -->
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Category</th>
                    <th scope="col">Name</th>
                    <th scope="col">Ingredients</th>
                    <th scope="col">Instructions</th>
                    <th scope="col">Author</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Fetch all users
                $users = $collection1->find();

                // Create an associative array of users indexed by their ID
                $userMap = [];
                foreach ($users as $user) {
                    // Convert ObjectId to string before using it as an index
                    $userId = (string) $user['_id'];
                    $userMap[$userId] = $user['username'];
                }

                // Assuming $recipes is fetched properly from MongoDB
                foreach ($recipes as $recipe): ?>
                    <tr>
                        <td><?= htmlspecialchars($recipe['category']) ?></td>
                        <td><?= htmlspecialchars($recipe['name']) ?></td>
                        <td>
                            <?php
                            if (!empty($recipe['ingredients'])) {
                                // Generate a unique identifier for this recipe's ingredients container
                                $containerId = 'ingredients_' . uniqid();
                            ?>
                            <div id="<?php echo $containerId; ?>" class="ingredients">
                                <?php
                                $firstIngredient = true;
                                foreach ($recipe['ingredients'] as $ingredient) {
                                    if ($firstIngredient) {
                                        // Display the first ingredient without hiding it
                                        echo '<div class="ingredient cursor-pointer" onclick="toggleIngredients(\'' . $containerId . '\', this)">' . $ingredient['name'] . ' <i class="fas fa-chevron-down"></i></div>';
                                        $firstIngredient = false;
                                    } else {
                                        // Hide the rest of the ingredients initially
                                        echo '<div class="ingredient hidden">' . $ingredient['name'] . '</div>';
                                    }
                                }
                                ?>
                            </div>
                            <?php } ?>
                        </td>
                        <td><textarea style="width: 100%" readonly><?= isset($recipe['instructions']) ? htmlspecialchars($recipe['instructions']) : 'Instructions not given' ?></textarea></td>
                        <td>
                            <?php
                                // Use the userMap to get the username based on the owner ID
                                echo isset($userMap[$recipe['owner']]) ? htmlspecialchars($userMap[$recipe['owner']]) : 'Unknown';
                            ?>
                        </td>
                        <td>
                            <a href="archive_recipe.php?id=<?= htmlspecialchars($recipe['_id']) ?>"><i class="fas fa-archive"></i></a>
                            |
                            <a href="remove_recipe.php?id=<?= htmlspecialchars($recipe['_id']) ?>"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Category</th>
                    <th scope="col">Name</th>
                    <th scope="col">Ingredients</th>
                    <th scope="col">Instructions</th>
                    <th scope="col">Author</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Fetch all users
                $users = $collection1->find();

                // Create an associative array of users indexed by their ID
                $userMap = [];
                foreach ($users as $user) {
                    // Convert ObjectId to string before using it as an index
                    $userId = (string) $user['_id'];
                    $userMap[$userId] = $user['username'];
                }
                
                foreach ($recipe_archives as $recipe): ?>
                    <tr>
                        <td><?= htmlspecialchars($recipe['category']) ?></td>
                        <td><?= htmlspecialchars($recipe['name']) ?></td>
                        <td>
                            <?php
                            if (!empty($recipe['ingredients'])) {
                                // Generate a unique identifier for this recipe's ingredients container
                                $containerId = 'ingredients_' . uniqid();
                            ?>
                            <div id="<?php echo $containerId; ?>" class="ingredients">
                                <?php
                                $firstIngredient = true;
                                foreach ($recipe['ingredients'] as $ingredient) {
                                    if ($firstIngredient) {
                                        // Display the first ingredient without hiding it
                                        echo '<div class="ingredient cursor-pointer" onclick="toggleIngredients(\'' . $containerId . '\', this)">' . $ingredient['name'] . ' <i class="fas fa-chevron-down"></i></div>';
                                        $firstIngredient = false;
                                    } else {
                                        // Hide the rest of the ingredients initially
                                        echo '<div class="ingredient hidden">' . $ingredient['name'] . '</div>';
                                    }
                                }
                                ?>
                            </div>
                            <?php } ?>
                        </td>
                        <td><?= isset($recipe['instructions']) ? htmlspecialchars($recipe['instructions']) : 'Instructions not given' ?></td>
                        <td>
                            <?php
                                // Use the userMap to get the username based on the owner ID
                                echo isset($userMap[$recipe['owner']]) ? htmlspecialchars($userMap[$recipe['owner']]) : 'Unknown';
                            ?>
                        </td>
                        <td>
                            <a href="restore_recipe.php?id=<?= htmlspecialchars($recipe['_id']) ?>"><i class="fas fa-arrow-rotate-left"></i></a>
                            |
                            <a href="remove_recipe_archive.php?id=<?= htmlspecialchars($recipe['_id']) ?>"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function toggleIngredients(containerId, clickedElement) {
            // Get all ingredients within the specified container
            var ingredients = document.querySelectorAll('#' + containerId + ' .ingredient');
            
            // Loop through all ingredients starting from the second one
            for (var i = 1; i < ingredients.length; i++) {
                // Toggle the "hidden" class to show/hide the ingredients
                ingredients[i].classList.toggle('hidden');
            }

            // Toggle the arrow icon between down and up
            var icon = clickedElement.querySelector('i');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');

            // Adjust the width of the container to match the width of the first ingredient
            var container = document.getElementById(containerId);
            var firstIngredientWidth = container.querySelector('.ingredient').offsetWidth;
            container.style.width = firstIngredientWidth + 'px';
        }
    </script>
</body>
</html>