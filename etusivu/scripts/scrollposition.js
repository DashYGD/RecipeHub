window.addEventListener('scroll', function() {
    document.getElementById('scrollPositionBtn').value = window.scrollY;
});

window.addEventListener('DOMContentLoaded', function() {
    var storedScrollPos = parseInt(document.getElementById('scrollPositionBtn').value, 10);

    if (!isNaN(storedScrollPos)) {
        window.scrollTo(0, storedScrollPos);
    }
});