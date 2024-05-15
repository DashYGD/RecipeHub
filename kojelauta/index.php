<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Include MongoDB library
require __DIR__ . '/../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

// Check connection
if (!$db) {
    die("MongoDB connection failed");
}

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: /kirjaudu/logout');
    exit();
} else {
    $user_id = $_SESSION['user'];
    //echo $user_id;
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <title>Kojelauta</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="../static\images\favicon.ico">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/W3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="../static/styles/core.css">
</head>
<body>

    <div id="layer_1" style="opacity:0;">
        <div id="sticky" style="z-index: 1;">
            <div id="navbar" class="navbar" style="z-index: 0">
                <div class="left-links">
                    <a id="myHomebutton" class="w3-hide-medium w3-hide-large"><span class="homebutton material-symbols-outlined">home</span></a>
                    <a class="hidden w3-hide-small" disabled><span class="material-symbols-outlined">home</span></a>
                    <a class="hidden" disabled><span class="material-symbols-outlined">home</span></a>
                    <a class="hidden" disabled><span class="material-symbols-outlined">home</span></a>
                </div>
                <div class="center-links">
                    <div class="dropdown">
                        <a class="dropbtn w3-hide-small">Reseptisi</a>
                        <div class="dropdown-content">
                            <a href="#">Option 1</a>
                            <a href="#">Option 2</a>
                            <a href="#">Option 3</a>
                        </div>
                    </div>
                <a class="w3-hide-small" href="#">Suosikit</a>
                <a class="w3-hide-small" href="#">Asetukset</a>
                </div>
                <div class="right-links">
                    <a href="/kirjaudu/logout" role="button"><span class="loginbutton material-symbols-outlined">logout</span></a>
                    <a href="#" role="button"><span class="cartbutton material-symbols-outlined">shopping_basket</span></a>
                    <a role="button" style="border-style:none;" id="myMenubutton" class="menubutton1"><span id="openmenu" class="menubutton material-symbols-outlined"></span></a>
                </div>
            </div>
            <div class="mySidebar" id="sidebar">
                <div class="sidebar w3-white w3-card w3-bar-block w3-animate-opacity" id="mySidebar">
                    <a href="/etusivu" class="w3-bar-item w3-button">Etusivu</a>
                </div>
            </div>
        </div>
    </div>

    <div id="layer_2" class="w3-card w3-content w3-white" style="opacity:0; max-width:900px; max-height:1071px;">
        <center>
            <div class="logo">
                <a href="/etusivu" id="title" role="button">R e c i p e H u b</a>
            </div>
            <div id="search-results_1">
                <?php include 'server/display-recipes.php'; ?>
            </div>
        </center>
    </div>

<div id="layer_3" class="w3-card w3-content w3-white" style="opacity:0; max-width:900px; max-height:1071px;">
    <center>
        <h1>Lisää</h1>
        <div id="add-recipe-btn" class="add-recipe-btn" onclick="openOverlay2()">+</div>
        <div id="overlay2" class="overlay2">
            <div id="overlay-content2" class="overlay-content2">
                <form id="add-recipe-form" method="POST">
                    <label for="recipe-name">Recipe Name:</label><br>
                    <input type="text" id="recipe-name" name="recipe-name" required><br><br>

                    <label for="category">Category:</label><br>
                    <select id="category" name="category" required>
                        <option value="Aamiainen">Aamiainen</option>
                        <option value="lounas">Lounas</option>
                        <option value="valipala">Välipala</option>
                        <option value="paivallinen">Päivällinen</option>
                        <option value="iltapala">Iltapala</option>
                        <!-- Add more options as needed -->
                    </select><br><br>

                    <label for="ingredient">Ingredient:</label><br>
                    <input type="text" id="ingredient" name="ingredient"><br><br>

                    <label for="quantity">Quantity:</label><br>
                    <input type="text" id="quantity" name="quantity"><br><br>

                    <label for="price">Price:</label><br>
                    <input type="number" id="price" name="price"><br><br>

                    <button type="button" onclick="addIngredient()">Add Ingredient</button><br><br>

                    <!-- Display ready ingredients -->
                    <div id="ready-ingredients"></div>

                    <label for="image">Image Upload:</label><br>
                    <input type="file" id="image" name="image" accept="image/*" required><br><br>

                    <input type="submit" value="Submit">
                </form>
            </div>
            <span class="close-btn material-symbols-outlined" onclick="closeOverlay2()">Close</span>
        </div>
    </center>
</div>

<div id="overlay1" class="overlay1">
    <div id="overlay-content1" class="overlay-content1"></div>
    <span class="close-btn material-symbols-outlined" onclick="closeOverlay1()">Close</span>
</div>

<div id="overlay3" class="overlay3">
    <div id="overlay-content3" class="overlay-content3"></div>
    <span class="close-btn" onclick="closeOverlay()">&times;</span>
</div>

<div id="ingredient-popup" class="ingredient-popup">
    <div id="ingredient-details" class="ingredient-details">
    </div>
    <span class="close-btn material-symbols-outlined" onclick="closeIngredientPopup()">Close</span>
</div>




    
<script type="text/javascript" src="../static/scripts/animation.js"></script>
<script type="text/javascript" src="../static/scripts/keyboard-accessibility.js"></script>
<script type="text/javascript" src="../static/scripts/sidebar.js"></script>
<script type="text/javascript" src="../static/scripts/navigationbar.js"></script>
<script type="text/javascript" src="scripts/overlay.js"></script>
<script type="text/javascript" src="scripts/submit.js"></script>
</body>
</html>