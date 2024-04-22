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
    echo $user_id;
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
                <a class="w3-hide-small w3-hide-medium" href="#">tyhjä</a>
                </div>
                <div class="right-links">
                    <a href="/kirjaudu/logout" role="button"><span class="loginbutton material-symbols-outlined">logout</span></a>
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

    <div id="layer_2" class="w3-card w3-content w3-white"  style="opacity:0; max-width:1440px; max-height:1071px;">
        <center>
            <h1>RecipeHub</h1>
            <div id="search-results_1">
            <?php include 'server/display-recipes.php'; ?>
            </div>
            <div id="overlay" class="overlay">
                <div id="overlay-content" class="overlay-content"></div>
                <span class="close-btn" onclick="closeOverlay()">&times;</span>
            </div>
        </center>
    </div>

    
<script type="text/javascript" src="../static/scripts/animation.js"></script>
<script type="text/javascript" src="../static/scripts/keyboard-accessibility.js"></script>
<script type="text/javascript" src="../static/scripts/sidebar.js"></script>
<script type="text/javascript" src="../static/scripts/navigationbar.js"></script>
</body>
</html>