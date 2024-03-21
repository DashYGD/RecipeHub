window.addEventListener('load', function() {
    function addAnimation() {
        setTimeout(function () {
            document.getElementById('base').classList.add('w3-animate-top');
            document.getElementById('base').style.opacity = "1";
        }, 500);
    }
    if (window.innerWidth > 991) {
        addAnimation();
    } else {
        document.getElementById('base').style.transition = "opacity 0.5s ease-in-out";
        document.getElementById('base').style.opacity = "1";
    }
});