<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container-fluid">
        <!-- Content -->
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Ingredients</th>
                    <th scope="col">Instructions</th>
                    <th scope="col">Author</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include MongoDB library
                require '../vendor/autoload.php';

                // Connect to MongoDB
                $mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
                $db = $mongoClient->reseptisovellus;

                // Select the recipes collection
                $collection = $db->recipes;

                // Fetch all documents from the collection
                $recipes = $collection->find();

                // Loop through each recipe and display its data
                foreach ($recipes as $recipe) {
                    echo "<tr>";
                    echo "<td>" . $recipe['name'] . "</td>";
                    echo "<td>" . $recipe['description'] . "</td>";
                    echo "<td>" . $recipe['ingredients'] . "</td>";
                    echo "<td>" . $recipe['instructions'] . "</td>";
                    echo "<td>" . $recipe['author'] . "</td>";
                    echo "<td>";
                    echo "<a href=\"edit_recipe.php?id=" . $recipe['_id'] . "\"><i class=\"fas fa-edit\"></i></a>";
                    echo " | ";
                    echo "<a href=\"remove_recipe.php?id=" . $recipe['_id'] . "\"><i class=\"fas fa-trash\"></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }

                // Display a message if no recipes are found
                if (count($recipes) == 0) {
                    echo "<tr><td colspan=\"6\">No recipes found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>