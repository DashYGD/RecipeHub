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
    header('Location: ../static/server/logout');
    exit();
} else {
    $username = $_SESSION['user'];
    echo $username;
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
</head>
<body id="base" style="opacity:0;">

    <div id="layer_1" class="bg w3-content" style="max-width:1564px; max-height:2100px;">
        <div id="sticky" style="z-index: 1;">
            <div id="navbar" class="navbar" style="z-index: 0">
                <div class="left-buttons">
                    <button id="myHomebutton" class="w3-left w3-hide-medium w3-hide-large"><span class="homebutton material-symbols-outlined">home</span></button>
                    <button class="hidden w3-left w3-hide-small" disabled><span class="material-symbols-outlined">home</span></button>
                    <a class="hidden"><button class="w3-left" disabled><span class="material-symbols-outlined">home</span></button></a>
                </div>
                <div class="center-links">
                    <a class="active w3-hide-small" href="/etusivu">Etusivu</a>
                    <a class="w3-hide-small" href="#">Tyhjä</a>
                    <a class="w3-hide-small" href="#">tyhjä</a>
                    <a class="w3-hide-small w3-hide-medium" href="#">tyhjä</a>
                </div>
                <div class="right-buttons">
                    <a href="/kirjaudu" role="button" class="w3-right"><span class="loginbutton material-symbols-outlined">login</span></a>
                    <button style="border-style:none;" id="myMenubutton" class="menubutton1 w3-right"><span id="openmenu" class="menubutton material-symbols-outlined"></span></button>
                </div>
            </div>
            <div class="mySidebar" id="sidebar">
                <div class="sidebar w3-white w3-card w3-bar-block w3-animate-opacity" id="mySidebar">
                    <a href="/etusivu" class="w3-bar-item w3-button">Etusivu</a>
                </div>
            </div>
        </div>

        <div id="layer_2" class="w3-content w3-white" style="max-width:1440px; max-height:1071px;">

        </div>
    </div>

    
<script type="text/javascript" src="../static/scripts/animation.js"></script>
<script type="text/javascript" src="../static/scripts/keyboard-accessibility.js"></script>
<script type="text/javascript" src="../static/scripts/sidebar.js"></script>
<script type="text/javascript" src="../static/scripts/navigationbar.js"></script>
</body>
</html>