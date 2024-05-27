<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:56123/");
$db = $mongoClient->reseptisovellus;

if (!$db) {
    die("MongoDB connection failed");
}

checkRememberMe($db);

function checkRememberMe($db) {
    if (isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
        
        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            return;
        }
        
        $result = $db->users->findOne(['token' => $token]);
        if ($result) {
            session_regenerate_id(true);
            if (isset($result['is_admin']) && $result['is_admin'] == 1) {
                $_SESSION['admin'] = true;
                header("Location: ../admin");
            } else {
                $_SESSION['user'] = (array) $result;
                $_SESSION['user_i'] = $result['_id'];
            }
        }
    }
}

$login_form_open = false;
function isUserLoggedIn() {
    return isset($_SESSION['user']) || isset($_SESSION['admin']);
}

if (isset($_SESSION['login_error'])) {
    $login_form_open = true;
    $login_error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

$registration_attempt = isset($_SESSION['registration_attempt']) && $_SESSION['registration_attempt'];

if ($registration_attempt) {
    if (isset($_SESSION['register_error'])) {
        $login_form_open = true;
        $register_error = $_SESSION['register_error'];
        unset($_SESSION['register_error']);
    } elseif (isset($_SESSION['register_success'])) {
        $login_form_open = true;
        $register_success = $_SESSION['register_success'];
        unset($_SESSION['register_success']);
    }
    unset($_SESSION['registration_attempt']);
}

$loggedIn = isUserLoggedIn();
$username = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : '';
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeHub</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="../static/styles/core.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rozha+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/W3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Neucha&family=Ropa+Sans:ital@0;1&display=swap" rel="stylesheet">
    <script>
        var isLoggedIn = <?php echo json_encode($loggedIn); ?>;
        var username = <?php echo json_encode($username); ?>;
        var loginFormOpen = <?php echo json_encode($login_form_open); ?>;
        console.log(loginFormOpen);
    </script>
</head>
<body>
<div id="welcomeMessage"></div>
<div id="layer_1" style="opacity:0;">
    <div id="sticky" style="z-index: 1;">
        <div id="navbar" class="navbar" style="z-index: 0">
            <div class="left-links">
                <a class="hidden" disabled><span class="material-symbols-outlined">home</span></a>
                <a class="hidden w3-hide-small" disabled><span class="material-symbols-outlined">home</span></a>
                <a class="hidden" disabled><span class="material-symbols-outlined">home</span></a>
            </div>
            <div class="logo_1">
                <a href="/etusivu" id="title" role="button">R e c i p e H u b</a>
            </div>
            <div class="center-links">
                <a class="w3-hide-small" href="#" onclick="showSection('section_1')">Hub</a>
                <a class="w3-hide-small" id="sectionButton_2" href="#" onclick="showSection('section_2')">Reseptini</a>
                <a class="w3-hide-small" id="sectionButton_3" href="#" onclick="showSection('section_3')">Suosikit</a>
            </div> 
            <div class="right-links">
                <a role="button" style="display:none;" id="settingsButton"><span class="material-symbols-outlined">settings</span></a>
                <a href="#" role="button" id="shoppingBasketButton_1" class="shopping-basket"><span id="shoppingBasketButton_2" class="cartbutton material-symbols-outlined">shopping_basket</span></a>
                <a id="authButton_1" role="button"><span class="loginbutton material-symbols-outlined" id="authButton_2">login</span></a>
                <a role="button" style="border-style:none;" id="myMenubutton" class="menubutton1"><span id="openmenu" class="menubutton material-symbols-outlined"></span></a>
            </div>
        </div>
        <div class="mySidebar" id="sidebar">
            <div class="sidebar w3-white w3-card w3-bar-block w3-animate-opacity" id="mySidebar">
                <a href="#" onclick="showSection('section_1')" class="w3-bar-item w3-button">Hub</a>
                <a href="#Reseptini" onclick="showSection('section_2')" class="w3-bar-item w3-button">Reseptini</a>
                <a href="#Suosikit" onclick="showSection('section_3')" class="w3-bar-item w3-button">Suosikit</a>
                <a href="#Asetukset" class="w3-bar-item w3-button">Asetukset</a>
            </div>
        </div>
    </div>
    <div class="shopping-basket-dropdown" id="shopping-basket-dropdown_1" style="display: none;"></div>
    <div style="display:block;" id="section_1">
        <div id="layer_2"  style="opacity:0;">
            <div class="search-container_1">
                <div class="buttons-container_1">
                    <input id="search-input_1" oninput="searchRecipes_1()" type="text" placeholder="Hae reseptiä...">
                    <input id="filter-button_1" onclick="openFilter()" type="button" placeholder="Filter">
                    <input type="submit" onclick="searchRecipes_1()" value="Hae">
                </div>
                <div class="filter-dropdown w3-card w3-green w3-container" id="filter-dropdownContainer" style="display:none;">
                    <select id="categoryDropdown" onchange="filterRecipesByCategory(this.value)">
                        <option value="">Ei kategoriaa</option>
                        <option value="aamiainen">Aamiainen</option>
                        <option value="lounas">Lounas</option>
                        <option value="välipala">Välipala</option>
                        <option value="päivällinen">Päivällinen</option>
                        <option value="jälkiruoka">Jälkiruoka</option>
                        <option value="iltapala">Iltapala</option>
                    </select>
                </div>
            </div>
            <div id="search-results_1"></div>
            <div id="overlay_1" class="overlay_1">
                <div id="overlay-content_1" class="overlay-content_1"></div>
            </div>
            <div id="overlay_2" class="overlay_2">
                <div id="overlay-content_2" class="overlay-content_2"></div>
            </div>
        </div>
    </div>
    <div style="display:none;" id="section_2">
        <div id="layer_3" style="opacity:0;">
            <div id="search-container_2"></div>
            <div id="search-results_2"></div>
            <div id="overlay_3" class="overlay_3">
                <div id="overlay-content_3" class="overlay-content_3"></div>
            </div>
            <div id="overlay_4" class="overlay_4">
                <div id="overlay-content_4" class="overlay-content_4"></div>
            </div>
        </div>
    </div>
    <div style="display:none;" id="section_3">
        <div id="layer_4" style="opacity:0;">
            <div id="search-container_3"></div>
            <div id="search-results_3"></div>
            <div id="overlay_5" class="overlay_5">
                <div id="overlay-content_5" class="overlay-content_5"></div>
            </div>
            <div id="overlay_6" class="overlay_6">
                <div id="overlay-content_6" class="overlay-content_6"></div>
            </div>
        </div>
    </div>
</div>
<div id="loginFormContainer" class="popupFormContainer">
    <div class="popupForm">
        <h3>Kirjaudu sisään</h3>
        <form id="loginForm" method="post" action="process_login.php">
            <input type="text" id="loginUsername" name="username" placeholder="Käyttäjänimi">
            <input type="password" id="loginPassword" name="password" placeholder="Salasana">
            <label for="rememberMe">
                <input type="checkbox" id="rememberMe" name="remember_me"> Muista minut
            </label>
            <button type="submit">Kirjaudu</button>
        </form>
        <?php if (isset($login_error)) : ?>
            <p class="error"><?php echo $login_error; ?></p>
        <?php endif; ?>
        <p>Ei vielä käyttäjää? <a href="#" onclick="openRegistrationForm()">Rekisteröidy</a></p>
        <button class="close-btn" onclick="closeLoginForm()">Sulje</button>
    </div>
</div>

<div id="registrationFormContainer" class="popupFormContainer">
    <div class="popupForm">
        <h3>Rekisteröidy</h3>
        <form id="registrationForm" method="post" action="process_registration.php">
            <input type="text" id="registerUsername" name="username" placeholder="Käyttäjänimi">
            <input type="email" id="registerEmail" name="email" placeholder="Sähköposti">
            <input type="password" id="registerPassword" name="password" placeholder="Salasana">
            <button type="submit">Rekisteröidy</button>
        </form>
        <?php if (isset($register_error)) : ?>
            <p class="error"><?php echo $register_error; ?></p>
        <?php elseif (isset($register_success)) : ?>
            <p class="success"><?php echo $register_success; ?></p>
        <?php endif; ?>
        <button class="close-btn" onclick="closeRegistrationForm()">Sulje</button>
    </div>
</div>
<script src="scripts/etusivu.js"></script>
</body>
</html>
