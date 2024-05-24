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
            if (isset($result['is_admin'])) {
                if ($result['is_admin'] == 1) {
                    $_SESSION['admin'] = true;
                    header("Location: admin");
                } else {
                    $_SESSION['user'] = (array) $result; // Store as array
                    $_SESSION['user_i'] = $result['_id'];
                    echo $_SESSION['user_i'];
                }
            } else {
                $_SESSION['user'] = (array) $result; // Store as array
                $_SESSION['user_i'] = $result['_id'];
            }
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
$username = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : '';
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
                    <a class="w3-hide-small" href="#" onclick="showSection('section_1')">Hub</a>
                    <a class="w3-hide-small" href="#" onclick="showSection('section_2')">Reseptini</a>
                    <a class="w3-hide-small w3-hide-medium" href="#">Suosikit</a>
                </div> 

                <div class="right-links">
                    <a role="button" style="display:none;" id="settingsButton"><span class="material-symbols-outlined">settings</span></a>
                    <a href="#" role="button" id="shoppingBasketButton" class="shopping-basket"><span class="cartbutton material-symbols-outlined">shopping_basket</span></a>
                    <a role="button"><span class="loginbutton material-symbols-outlined" id="authButton">login</span></a>
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

        <div style="display:block;" id="section_1">
    <div id="layer_2"  style="opacity:0;">
        <div class="search-container">
            
            <div class="buttons-container">
                <input id="search-input_1" oninput="searchRecipes1(event)" type="text" placeholder="Hae reseptiä...">
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



        <div id="search-results_2"></div>
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
</div>

<div style="display:none;" id="section_2">
<div class="shopping-basket-dropdown" id="shopping-basket-dropdown" style="display: none;">

</div>

    <div id="layer_10" class="w3-card w3-content w3-white" style="max-width:900px; max-height:1071px;">
        
    <center>
            <div class="logo2">
                <a href="/etusivu" id="title" role="button">R e c i p e H u b</a>
            </div>
            <div id="search-results_3"></div>
        </center>
    </div>

    
    <div id="add-recipe-btn" class="add-recipe-btn" onclick="openOverlay5()">+</div>

<div id="layer_11" class="w3-card w3-content w3-white" style="opacity:0; max-width:900px; max-height:1071px;">
    <center>
        <div id="overlay5" class="overlay5">
            <div id="overlay-content5" class="overlay-content5">
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
    </select><br><br>

    <label for="ingredient">Ingredient:</label><br>
    <input type="text" id="ingredient" name="ingredient"><br><br>

    <label for="quantity">Quantity:</label><br>
    <input type="number" id="quantity" name="quantity"><br><br>

    <label for="unit">Unit:</label><br>
    <select id="unit" name="unit">
        <option value="g">g</option>
        <option value="kg">kg</option>
        <option value="ml">ml</option>
        <option value="l">l</option>
        <option value="pcs">pcs</option>
    </select><br><br>

    <label for="price">Price:</label><br>
    <input type="number" id="price" name="price"><br><br>

    <button type="button" onclick="addIngredient()">Add Ingredient</button><br><br>

    <div id="ready-ingredients"></div>

    <label for="image">Image Upload:</label><br>
    <input type="file" id="image" name="image" accept="image/*" required><br><br>

    <input type="submit" value="Submit">
</form>


            </div>
            <span class="close-btn material-symbols-outlined" onclick="closeOverlay5()">Close</span>
        </div>
    </center>
</div>

<div id="overlay6" class="overlay6">
    <div id="overlay-content6" class="overlay-content6"></div>
    <span class="close-btn material-symbols-outlined" onclick="closeOverlay6()">Close</span>
</div>

<div id="overlay7" class="overlay7">
    <div id="overlay-content7" class="overlay-content7"></div>
    <span class="close-btn" onclick="closeOverlay7()">&times;</span>
</div>

<div id="ingredient-popup" class="ingredient-popup">
    <div id="ingredient-details" class="ingredient-details">
    </div>
    <span class="close-btn material-symbols-outlined" onclick="closeIngredientPopup()">Close</span>
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
        function attachEventListeners() {
    var basketButton = document.getElementById('shoppingBasketButton');
    var dropdown = document.getElementById('shopping-basket-dropdown');
    var body = document.body;

    if (basketButton) {
        basketButton.addEventListener('click', function(event) {
            event.stopPropagation();
            dropdown.classList.toggle('show');
            
            if (dropdown.classList.contains('show')) {
                fetchShoppingBasket();
                body.classList.add('noscroll'); 
            } else {
                body.classList.remove('noscroll'); 
            }
        });
    }
    var authButton = document.getElementById('authButton');
    var container1 = document.getElementById("layer_3");
    var container2 = document.getElementById("layer_4");

    if (isLoggedIn) {
        authButton.textContent = 'logout';
        authButton.onclick = function() {
            window.location.href = '../static/server/logout.php';
        };
    } else {
        authButton.textContent = 'login';
        authButton.onclick = function() {
            container1.style.display = 'flex';
            container2.style.display = 'block';
        };
    }
    document.addEventListener('click', function(event) {
    if (!container2.contains(event.target) && event.target !== authButton) {
        container1.style.display = 'none';
        container2.style.display = 'none';
    }
});


    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && !basketButton.contains(event.target)) {
            dropdown.classList.remove('show');
            body.classList.remove('noscroll'); 
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    attachEventListeners();
});
function showSection(sectionId) {
    attachEventListeners();
document.getElementById('section_1').style.display = 'none';
document.getElementById('section_2').style.display = 'none';

if (document.getElementById('section_2').style.display == 'block') {
    searchRecipes();
    console.log("ok");
}

document.getElementById(sectionId).style.display = 'block';
}

    showSection('section_1');

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
<script type="text/javascript" src="scripts/overlay.js"></script>
<script type="text/javascript" src="scripts/submit.js"></script>
<script type="text/javascript" src="scripts/fetch_list.js"></script>
</body>
</html>
