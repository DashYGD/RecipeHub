window.addEventListener('load', function() {
    function addAnimation() {
        setTimeout(function () {
            document.getElementById('layer_1').classList.add('w3-animate-top');
            document.getElementById('layer_1').style.opacity = "1";
            document.getElementById('layer_2').style.transition = "opacity 0.5s ease-in-out";
            document.getElementById('layer_2').style.opacity = "1";
        }, 500);
    }
        addAnimation();
});