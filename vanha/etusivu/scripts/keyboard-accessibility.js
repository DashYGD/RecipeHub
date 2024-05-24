document.addEventListener('mousedown', function () {
    document.body.classList.add('using-mouse');
});

document.addEventListener('keydown', function () {
    document.body.classList.remove('using-mouse');
});