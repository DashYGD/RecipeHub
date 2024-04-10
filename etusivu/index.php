<!DOCTYPE html>
<html lang="en">
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
                    <a class="w3-hide-small" href="#">tyhjä</a>
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

    <div id="layer_2"  style="opacity:0;">
        <div class="search-container">
            <input type="text" placeholder="Hae reseptiä...">
            <input type="submit" value="Hae">
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
                <img src="images/illallinen_uusi.png" alt="paivallinen">
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

    <script>
        function navigate(mealType) {
            // Tässä voit toteuttaa toiminnallisuuden, joka vie käyttäjän valitun ateriatyypin reseptikortille.
            // Voit esimerkiksi käyttää JavaScriptin window.location -ominaisuutta.
            console.log('Navigating to ' + mealType + ' recipe...');
        }
    </script>    
<script type="text/javascript" src="../static/scripts/animation.js"></script>
<script type="text/javascript" src="../static/scripts/keyboard-accessibility.js"></script>
<script type="text/javascript" src="../static/scripts/sidebar.js"></script>
<script type="text/javascript" src="../static/scripts/navigationbar.js"></script>
</body>
</html>
