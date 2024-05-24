const menubutton1 = document.querySelector('.menubutton1');
const menubutton2 = document.querySelector('.menubutton');

if (menubutton1) {
    menubutton1.addEventListener('click', w3_open);
}

var x = 0;

function w3_open() {
    if (x == 0) {
        document.getElementById("mySidebar").style.display = "block";
        document.getElementById("myMenubutton").style.transition = ".75s";
        document.getElementById("myMenubutton").style.backgroundColor = "grey";
        menubutton2.classList.add("openMenu");
        x = 1;
    } else {
        document.getElementById("mySidebar").style.display = "none";
        document.getElementById("myMenubutton").style.transition = ".75s";
        document.getElementById("myMenubutton").style.backgroundColor = "#333";
        menubutton2.classList.remove("openMenu");
        x = 0;
    }
}

if (menubutton1) {
    menubutton1.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            w3_open();
        }
    });
}