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
                    header("Location: ../admin");
                } else {
                    $_SESSION['user'] = (array) $result; // Store as array
                    $_SESSION['user_i'] = $result['_id'];
                }
            } else {
                $_SESSION['user'] = (array) $result; // Store as array
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
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


        <div class="icon-container_1">
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
<div class="shopping-basket-dropdown" id="shopping-basket-dropdown_2" style="display: none;"></div>

    <div id="layer_3" class="w3-card w3-content w3-white" style="max-width:900px;">
        
    <center>
            <div class="logo_2">
                <a href="#" id="title" role="button">R e s e p t i n i</a>
            </div>
            <div id="search-results_2"></div>
        </center>
    </div>

    
    <div id="add-recipe-btn" class="add-recipe-btn">+</div>

<div id="layer_4" class="w3-card w3-content w3-white" style="max-width:900px; max-height:1071px;">
    <center>
        <div id="overlay_5" class="overlay_5">
            <div id="overlay-content_5" class="overlay-content_5">
            <form id="add-recipe-form" method="POST">
    <label for="recipe-name">Reseptin nimi:</label><br>
    <input type="text" id="recipe-name" name="recipe-name" required><br><br>

    <label for="category">Kategoria:</label><br>
    <select id="category" name="category" required>
        <option value="Aamiainen">Aamiainen</option>
        <option value="lounas">Lounas</option>
        <option value="valipala">Välipala</option>
        <option value="paivallinen">Päivällinen</option>
        <option value="iltapala">Iltapala</option>
    </select><br><br>

    <label for="ingredient">Ainesosa:</label><br>
    <input type="text" id="ingredient" name="ingredient"><br><br>

    <label for="quantity">Määrä:</label><br>
    <input type="number" id="quantity" name="quantity"><br><br>

    <label for="unit">Yksikkö:</label><br>
    <select id="unit" name="unit">
        <option value="g">g</option>
        <option value="kg">kg</option>
        <option value="ml">ml</option>
        <option value="l">l</option>
        <option value="kpl">kpl</option>
    </select><br><br>

    <label for="price">Hinta:</label><br>
    <input type="number" id="price" name="price"><br><br>

    <button type="button" onclick="addIngredient()">Tallenna ainesosa</button><br><br>

    <div id="ready-ingredients"></div>

    <label for="image">Kuva:</label><br>
    <input type="file" id="image" name="image" accept="image/*" required><br><br>

    <label for="instructions">Ohjeet:</label><br>
    <textarea id="instructions" name="instructions" rows="10" cols="50" required></textarea><br><br>

    <input type="submit" value="Luo resepti">
</form>



            </div>
        </div>
    </center>
</div>

<div id="overlay_6" class="overlay_6">
    <div id="overlay-content_6" class="overlay-content_6"></div>
</div>

<div id="ingredient-popup" class="ingredient-popup">
    <div id="ingredient-details" class="ingredient-details">
    </div>
    <span class="close-btn material-symbols-outlined" onclick="closeIngredientPopup()">Close</span>
</div>
</div>

<div style="display:none;" id="section_3">
    <div id="layer_7" class="w3-card w3-content w3-white" style="max-width:900px;">
        <center>
            <div class="logo_2">
                <a href="#" id="title" role="button">S u o s i k i t</a>
            </div>
            <div id="search-results_3"></div>
        </center>
        </div>
</div>

        <div id="overlay_7" class="overlay_7">
            <div id="overlay-content_7" class="overlay-content_7"></div>
        </div>



    <div id="layer_5" style="display:none;">
    <div id="layer_6">
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
        var basketButton_1 = document.getElementById('shoppingBasketButton_1');
        var basketButton_2 = document.getElementById('shoppingBasketButton_2');
        var container1 = document.getElementById("layer_5");
        var container2 = document.getElementById("layer_6");
        var dropdown_1 = document.getElementById('shopping-basket-dropdown_1');
        
        var form_1 = document.getElementById('overlay_5');
        var form_2 = document.getElementById('overlay-content_5');

        var overlay_1 = document.getElementById('overlay_6');
        var overlay_2 = document.getElementById('overlay-content_6');
        var overlay_3 = document.getElementsByClassName('recipe-card_2');

        var overlay_4 = document.getElementById('overlay_1');
        var overlay_5 = document.getElementById('overlay-content_1');
        var overlay_6 = document.getElementsByClassName('recipe-card');

        var overlay_7 = document.getElementById('overlay_7');
        var overlay_8 = document.getElementById('overlay-content_7');
        var overlay_9 = document.getElementsByClassName('recipe-card_3');

        var form_button = document.getElementById('add-recipe-btn');
        var body = document.body;
        
        

        if (basketButton_1 && isLoggedIn) {
            basketButton_1.addEventListener('click', function(event) {
                event.stopPropagation();
                dropdown_1.classList.toggle('show');
                
                if (dropdown_1.classList.contains('show')) {
                    fetchShoppingBasket();
                    body.classList.add('noscroll'); 
                } else {
                    body.classList.remove('noscroll'); 
                }
            });
        } else {
            basketButton_1.onclick = function() {
                container1.style.display = 'flex';
                container2.style.display = 'block';
                console.log("what");
            };

        }

        var authButton_1 = document.getElementById('authButton_1');
        var authButton_2 = document.getElementById('authButton_2');
        var sectionButton = document.getElementById('sectionButton_2');
        var sectionButton3 = document.getElementById('sectionButton_3');
        if (loginFormOpen) {
            toggleLoginForm(true); // Open the login form
        }




        if (isLoggedIn) {
            authButton_2.textContent = 'logout';
            authButton_1.onclick = function() {
                localStorage.setItem('currentSection', 'section_1');
                window.location.href = '../static/server/logout.php';
            };
        } else {
            authButton_2.textContent = 'login';
            authButton_1.onclick = function() {
                container1.style.display = 'flex';
                container2.style.display = 'block';
            };
            
        }

        document.addEventListener('click', function(event) {
            if (!container2.contains(event.target) && event.target !== authButton_2 && event.target !== authButton_1 && event.target !== sectionButton && event.target !== sectionButton3 && event.target !== basketButton_2 && event.target !== basketButton_1) {
                container1.style.display = 'none';
            }
        });

        document.addEventListener('click', function(event) {
            if (!dropdown_1.contains(event.target) && !basketButton_2.contains(event.target)) {
                dropdown_1.classList.remove('show');
                body.classList.remove('noscroll'); 
            }
        });

        document.addEventListener('click', function(event) {
            if (!form_2.contains(event.target) && event.target !== form_button) {
                form_1.style.display = 'none';
            } else {
                form_1.style.display = 'block';
            }
        });

        document.addEventListener('click', function(event) {
            var clickedInsideOverlay2 = overlay_2.contains(event.target);
            var clickedInsideOverlay3 = false;

            for (var i = 0; i < overlay_3.length; i++) {
                if (overlay_3[i].contains(event.target)) {
                    clickedInsideOverlay3 = true;
                    break;
                }
            }

            if (!clickedInsideOverlay2 && !clickedInsideOverlay3) {
                overlay_1.style.display = 'none';
            }
        });
        

        document.addEventListener('click', function(event) {
            var clickedInsideOverlay5 = overlay_5.contains(event.target);
            var clickedInsideOverlay6 = false;

            for (var i = 0; i < overlay_6.length; i++) {
                if (overlay_6[i].contains(event.target)) {
                    clickedInsideOverlay6 = true;
                    break;
                }
            }

            if (!clickedInsideOverlay5 && !clickedInsideOverlay6) {
                overlay_4.style.display = 'none';
            }
        });

        document.addEventListener('click', function(event) {
            var clickedInsideOverlay8 = overlay_8.contains(event.target);
            var clickedInsideOverlay9 = false;

            for (var i = 0; i < overlay_9.length; i++) {
                if (overlay_9[i].contains(event.target)) {
                    clickedInsideOverlay9 = true;
                    break;
                }
            }

            if (!clickedInsideOverlay8 && !clickedInsideOverlay9) {
                overlay_7.style.display = 'none';
            }
        });
    }

    function toggleLoginForm(show) {
        var container1 = document.getElementById("layer_5");
        var container2 = document.getElementById("layer_6");
        if (show) {
            container1.style.display = 'flex';
            container2.style.display = 'block';
        } else {
            container1.style.display = 'none';
            container2.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        
        var storedSection = localStorage.getItem('currentSection');
        console.log(storedSection);
        if (storedSection) {
            showSection(storedSection);
        } else {
            showSection('section_1');
        }

        
        attachEventListeners();
    });

    function showSection(sectionId) {
        if ((sectionId === 'section_2' || sectionId === 'section_3') && !isLoggedIn) {
            var container1 = document.getElementById("layer_5");
            var container2 = document.getElementById("layer_6");
            container1.style.display = 'flex';
            container2.style.display = 'block';
        } else {
            document.getElementById('section_1').style.display = 'none';
            document.getElementById('section_2').style.display = 'none';
            document.getElementById('section_3').style.display = 'none';
            document.getElementById(sectionId).style.display = 'block';
            if (sectionId === 'section_2') {
                
                localStorage.setItem('currentSection', sectionId);
                searchRecipes_2();
                console.log("oaak");
            } else if (sectionId === 'section_3') {
                localStorage.setItem('currentSection', sectionId);
                searchRecipes_3();
            } else {
                
                localStorage.setItem('currentSection', sectionId);
                console.log("oook");
            }
            
        
            console.log(localStorage.getItem('currentSection'));
            console.log("works");
        }
    }

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
