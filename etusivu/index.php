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
            if ($result['is_admin']) {
                if ($result['is_admin'] == 1) {
                $_SESSION['admin'] = true;
                header("Location: admin");
                } else {
                    $_SESSION['user'] = $result['_id'];
                }
            } else {
                $_SESSION['user'] = $result['_id'];
            }
        } else {
        }
    }
}

function isUserLoggedIn() {
    return isset($_SESSION['user']) || isset($_SESSION['admin']);
}


if (isset($_SESSION['login_error'])) {
    $login_error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

$registration_attempt = isset($_SESSION['registration_attempt']) && $_SESSION['registration_attempt'];

if ($registration_attempt) {
    if (isset($_SESSION['register_error'])) {
        $register_error = $_SESSION['register_error'];
        unset($_SESSION['register_error']);
    } elseif (isset($_SESSION['register_success'])) {
        $register_success = $_SESSION['register_success'];
        unset($_SESSION['register_success']);
    }
    unset($_SESSION['registration_attempt']);
}

$loggedIn = isUserLoggedIn();
$username = isset($_SESSION['user']) ? $_SESSION['user'] : '';
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseptikortti</title>
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Neucha&family=Ropa+Sans:ital@0;1&display=swap" rel="stylesheet">
<script>
        var isLoggedIn = <?php echo json_encode($loggedIn); ?>;
        var username = <?php echo json_encode($username); ?>;
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
                <div class="logo">
                    <a href="/etusivu" id="title" role="button">R e c i p e H u b</a>
                </div>
                

                <div class="center-links">
                    <a class="w3-hide-small" href="#">tyhjä</a>
                    <a class="w3-hide-small" href="#">Suosikit</a>
                    <a class="w3-hide-small w3-hide-medium" href="#">tyhjä</a>
                </div> 

                <div class="right-links">
                    <a role="button" style="display:none;" id="settingsButton"><span class="material-symbols-outlined">settings</span></a>
                    <a role="button"><span class="loginbutton material-symbols-outlined" id="authButton">login</span></a>
                    <a role="button" style="border-style:none;" id="myMenubutton" class="menubutton1"><span id="openmenu" class="menubutton material-symbols-outlined"></span></a>
                </div>
                
            </div>
            <div class="mySidebar" id="sidebar">
                <div class="sidebar w3-white w3-card w3-bar-block w3-animate-opacity" id="mySidebar">
                    <a href="/etusivu" class="w3-bar-item w3-button">Etusivu</a>
                </div>
            </div>
            <div id="settingsDropdown" style="display:none;">
                        <a href="#">Option 1</a>
                        <a href="#">Option 2</a>
                        <a href="#">Option 3</a>
                    </div>
                
            </div>
        </div>

    <div id="layer_2"  style="opacity:0;">
        <div class="search-container">
            
            <div class="buttons-container">
                <input id="search-input_1" oninput="searchRecipes(event)" type="text" placeholder="Hae reseptiä...">
                <input id="filter-button_1" onclick="openFilter()" type="button" placeholder="Filter">
                <input type="submit" value="Hae">
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
        <div id="overlay" class="overlay">
            <div id="overlay-content" class="overlay-content"></div>
            <span class="close-btn" onclick="closeOverlay()">&times;</span>
        </div>


        <div class="icon-container">
            <div class="icon" onclick="navigate('aamupala')">
                <img src="images/aamupala_uusi.png" alt="Aamupala">
                <p>Aamupala</p>
            </div>

            <div class="icon" onclick="navigate('lounas')">
                <img src="images/lounas_uusi.png" alt="Lounas">
                <p>Lounas</p>
            </div>

            <div class="icon" onclick="navigate('valipala')">
                <img src="images/valipala_uusi.png" alt="Valipala">
                <p>Välipala</p>
            </div>

            <div class="icon" onclick="navigate('paivallinen')">
                <img src="images/paivallinen_uusi.png" alt="paivallinen">
                <p>Päivällinen</p>
            </div>

            <div class="icon" onclick="navigate('iltapala')">
                <img src="images/iltapala_uusi.png" alt="iltapala">
                <p>Iltapala</p>
            </div>

            <div class="icon" onclick="navigate('jalkiruoka')">
                <img src="images/jalkiruoka_uusi.png" alt="jalkiruoka">
                <p>Jälkiruoka</p>
            </div>
        </div>
    </div>

    <div id="layer_3" style="display:none;">
    <div id="layer_4">
        <center>
            <h1>RecipeHub Kirjautuminen</h1>
        </center>

        <form class="w3-container" action="server/process.php" method="POST" id="login-in">
            <div class="w3-section">
                <label><b>Sähköposti/Käyttäjänimi</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Sähköposti/Käyttäjänimi" name="email-username_1" id="email-username_1" required>
                <label><b>Salasana</b></label>
                <input class="w3-input w3-border" type="password" placeholder="Salasana" name="password_1" id="password_1" required>
                <input class="w3-check w3-margin-top" type="checkbox" name="muista_minut" checked="checked"> Muista minut</button>
                <span class="w3-right w3-margin-top w3-padding w3-hide-small">Unohditko <a href="#">salasanasi?</a></span>
                <input class="w3-button w3-block w3-green w3-section w3-padding" type="submit" value="Kirjaudu sisään">
                <?php if (isset($login_error)) { echo "<p style='color: red;'>$login_error</p>"; } ?>
                <?php if (isset($register_success)) { echo "<p style='color: red;'>$register_success</p>"; } ?>
                <p>Ei vielä käyttäjää? <a href="#" onclick="toggleForms()">Rekisteröidy</a></p>
            </div>
        </form>

        <form class="w3-container" action="process" method="POST" id="register-in">
            <div class="w3-section">
                <label><b>Käyttäjänimi</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Käyttäjänimi" name="name_2" id="name_2" required>
                <label><b>Sähköposti</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="email" name="email_2" id="email_2" placeholder="Sähköposti" required>
                <label><b>Salasana</b></label>
                <input class="w3-input w3-border" type="password" name="password_2" id="password_2" placeholder="Salasana" required>
                <?php if (isset($register_error)) { echo "<p style='color: red;'>$register_error</p>"; } ?>
                <input class="w3-button w3-block w3-green w3-section w3-padding" type="submit" value="Luo käyttäjä">
                <p>On jo käyttäjä? <a href="#" onclick="toggleForms()">Kirjaudu sisään</a></p>
            </div>
        </form>
    </div></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        var authButton = document.getElementById('authButton');
        var settingsButton = document.getElementById('settingsButton');
        var settingsDropdown = document.getElementById('settingsDropdown');
        var welcomeMessage = document.getElementById('welcomeMessage');
        var container1 = document.getElementById("layer_3");

    if (isLoggedIn) {
        authButton.textContent = 'logout';
        authButton.onclick = function() {
            window.location.href = '../static/server/logout.php';
        };

        settingsButton.style.display = 'flex';
        welcomeMessage.textContent = 'Welcome ' + username;
        welcomeMessage.style.display = 'block';
        setTimeout(function() {
            welcomeMessage.style.display = 'none';
        }, 5000);

        settingsButton.addEventListener('click', function() {
            settingsDropdown.style.display = settingsDropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function(event) {
            if (!settingsButton.contains(event.target) && !settingsDropdown.contains(event.target)) {
                settingsDropdown.style.display = 'none';
            }
        });
    } else {
        authButton.textContent = 'login';
        authButton.onclick = function() {
            container1.style.display = 'flex';
        };

        settingsButton.style.display = 'none';
    }
});



        function toggleForms() {
            var loginForm = document.getElementById('login-in');
            var registerForm = document.getElementById('register-in');

            if (loginForm.style.display === 'none') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
            }
        }

        var registrationAttempt = <?php echo $registration_attempt ? 'true' : 'false'; ?>;

        if (registrationAttempt) {
            if ("<?php echo isset($register_success); ?>") {
                document.getElementById("register-in").style.display = "none";
                document.getElementById("login-in").style.display = "block";
            } else {
                document.getElementById("register-in").style.display = "block";
                document.getElementById("login-in").style.display = "none";
            }
        } else {
            document.getElementById("login-in").style.display = "block";
            document.getElementById("register-in").style.display = "none";
        }
    </script>    
<script type="text/javascript" src="scripts/animation.js"></script>
<script type="text/javascript" src="../static/scripts/keyboard-accessibility.js"></script>
<script type="text/javascript" src="../static/scripts/sidebar.js"></script>
<script type="text/javascript" src="../static/scripts/navigationbar.js"></script>
<script type="text/javascript" src="scripts/fetch_recipe.js"></script>
<script type="text/javascript" src="scripts/filter.js"></script>
<script type="text/javascript" src="scripts/navigation.js"></script>
</body>
</html>
