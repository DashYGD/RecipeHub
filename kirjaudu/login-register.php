<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include MongoDB library
require __DIR__ . '/../vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://65.21.248.139:27017/");
$db = $mongoClient->reseptisovellus;

// Check connection
if (!$db) {
    die("MongoDB connection failed");
}

checkRememberMe($db);

function checkRememberMe($db) {
    if (isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
        $result = $db->kayttajat->findOne(['token' => $token]);

        if ($result) {
            if ($result['is_admin'] == 1) {
                $_SESSION['admin'] = true;
                header("Location: admin");
            } else {
                $_SESSION['user'] = $result['username'];
                header("Location: kojelauta");
            }
            exit;
        }
    }
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
?>



<!DOCTYPE html>
<html>
<head>
    <title>Kirjaudu Sisään</title>
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

<style>
    *:focus {
        outline: none;
    }
</style>

<body id="base" style="opacity:0;">

  <div id="home" class="bg w3-content" style="max-width:1564px; max-height:2100px;">
      <div id="layer_1" class="w3-content w3-white" style="max-width:1350px; max-height:2100px;">

          <div id="sticky" style="z-index: 1;">
              <div id="navbar" class="navbar" style="z-index: 0">
                  <div class="left-buttons">
                      <button id="myHomebutton" class="w3-left w3-hide-medium w3-hide-large"><span class="homebutton material-symbols-outlined">home</span></button>
                      <button class="hidden w3-left w3-hide-small" disabled><span class="material-symbols-outlined">home</span></button>
                      <a class="hidden"><button class="w3-left" disabled><span class="material-symbols-outlined">home</span></button></a>
                  </div>
                  <div class="center-links">
                      <a class="active w3-hide-small" href="/etusivu">Etusivu</a>
                      <a class="w3-hide-small" href="/toiminta">Toiminta</a>
                      <a class="w3-hide-small" href="/tapahtumakalenteri">Tapahtumakalenteri</a>
                      <a class="w3-hide-small w3-hide-medium" href="/kuvagalleria">Kuvagalleria</a>
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
          
          
          <div id="layer_2">
            <center>
                <h1>ReceHub Kirjautuminen</h1>
            </center>

            <form class="w3-container" action="process" method="POST" id="login-in">
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
          </div>
      </div>
  </div>

<script>
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

<script type="text/javascript" src="../static/scripts/animation.js"></script>
<script type="text/javascript" src="../static/scripts/keyboard-accessibility.js"></script>
<script type="text/javascript" src="../static/scripts/sidebar.js"></script>
<script type="text/javascript" src="../static/scripts/navigationbar.js"></script>
</body>
</html>
