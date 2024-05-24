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
if (!isset($_SESSION['admin'])) {
    header('Location: /kirjaudu/logout');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>Admin</title>
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
    <link rel="stylesheet" href="../static/styles/core.css">
</head>
<body>
    <div id="layer_1">
        <div id="sticky" style="z-index: 1;">
            <div id="navbar" class="navbar" style="z-index: 0">
                <div class="left-links">
                    <a id="myHomebutton" class="w3-hide-medium w3-hide-large"><span class="homebutton material-symbols-outlined">home</span></a>
                    <a class="hidden w3-hide-small" disabled><span class="material-symbols-outlined">home</span></a>
                    <a class="hidden" disabled><span class="material-symbols-outlined">home</span></a>
                </div>
                <div class="center-links">
                    <a class="active w3-hide-small" href="/etusivu">Etusivu</a>
                    <a class="w3-hide-small" href="?page=users">Käyttäjät</a>
                    <a class="w3-hide-small" href="?page=recipes">Reseptit</a>
                </div>
                <div class="right-links">
                    <a href="../static/server/logout.php" role="button"><span class="loginbutton material-symbols-outlined">login</span></a>
                    <a role="button" style="border-style:none;" id="myMenubutton" class="menubutton1"><span id="openmenu" class="menubutton material-symbols-outlined"></span></a>
                </div>
            </div>
            <div class="mySidebar" id="sidebar">
                <div class="sidebar w3-white w3-card w3-bar-block w3-animate-opacity" id="mySidebar">
                    <a href="/etusivu" class="w3-bar-item w3-button">Etusivu</a>
                </div>
                <div class="sidebar w3-white w3-card w3-bar-block w3-animate-opacity" id="mySidebar">
                    <a href="?page=recipes" class="w3-bar-item w3-button">Reseptit</a>
                </div>
                <div class="sidebar w3-white w3-card w3-bar-block w3-animate-opacity" id="mySidebar">
                    <a href="?page=users" class="w3-bar-item w3-button">Käyttäjät</a>
                </div>
            </div>
        </div>
    </div>

    <div id="layer_2" class="w3-card w3-content w3-white" style="max-width:1440px; max-height:1071px;">
        <center>
            <h1>RecipeHub</h1>
        </center>

        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'recipes';
        switch ($page) {
            case 'recipes':
                include 'recipes.php';
                break;
            case 'users':
                include 'users.php';
                break;
            default:
                include 'recipes.php';
                break;
        }
        ?>
    </div>
    
    <script type="text/javascript" src="../static/scripts/animation.js"></script>
    <script type="text/javascript" src="../static/scripts/keyboard-accessibility.js"></script>
    <script type="text/javascript" src="../static/scripts/sidebar.js"></script>
    <script type="text/javascript" src="../static/scripts/navigationbar.js"></script>
</body>
</html>