<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

// Connect to MongoDB
$mongoClient = new Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Check connection
if (!$db) {
    die("MongoDB connection failed");
}

$recipesCollection = $db->list;

if (isset($_GET['id'])) {
    $recipeId = $_GET['id'];
    $recipe = $recipesCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($recipeId)]);

    if ($recipe) {
        // Calculate total price
        $totalPrice = 0;
        foreach ($recipe['ingredients'] as $ingredient) {
            $totalPrice += floatval($ingredient['price']);
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ostoslista</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f0f0f0;
                }
                .container {
                    max-width: 800px;
                    margin: 20px auto;
                    padding: 20px;
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                h2 {
                    font-size: 36px;
                    font-weight: bold;
                    color: #333;
                    margin-bottom: 20px;
                }
                ul {
                    list-style-type: none;
                    padding: 0;
                }
                li {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    font-size: 20px;
                    margin-bottom: 10px;
                    padding: 10px;
                    border-bottom: 1px solid #ccc;
                }
                li:last-child {
                    border-bottom: none;
                }
                .name,
                .quantity,
                .unit,
                .price {
                    flex: 1;
                    margin-right: 10px;
                }
                .quantity,
                .unit,
                .price {
                    text-align: left;
                }
                .divider {
                    border-top: 1px solid #ccc;
                    margin-top: 10px;
                }
                .total-price {
                    font-weight: bold;
                    margin-top: 10px;
                    font-size: 24px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Ostoslista</h2>
                <ul>
                    <li>
                        <span class="name">Tuote:</span>
                        <span class="quantity">Määrä:</span>
                        <span class="unit">Yksikkö:</span>
                        <span class="price">Hinta:</span>
                    </li>
                    <?php foreach ($recipe['ingredients'] as $ingredient): ?>
                        <li>
                            <span class="name"><?= $ingredient['name'] ?></span>
                            <span class="quantity"><?= $ingredient['quantity'] ?></span>
                            <span class="unit"><?= $ingredient['unit'] ?></span>
                            <span class="price"><?= $ingredient['price'] ?>€</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="divider"></div>
                <div class="total-price">Kokonaishinta: <?= number_format($totalPrice, 2) ?>€</div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo 'Recipe not found';
    }
}
?>
