function openOverlay1(recipe) {
var overlay = document.getElementById('overlay1');
var overlayContent = document.getElementById('overlay-content1');

overlayContent.innerHTML = '';

var ingredientsHtml = '';
if (recipe.ingredients) {
    for (var j = 0; j < recipe.ingredients.length; j++) {
        ingredientsHtml += '<p>' + recipe.ingredients[j].name + ' (' + recipe.ingredients[j].quantity + ') - ' + recipe.ingredients[j].price.toFixed(2) + ' €</p>';
    }
}

overlayContent.innerHTML = '<img src="' + recipe.image + '" alt="' + recipe.name + '">' +
                            '<h2>' + recipe.name + '</h2>' +
                            '<p><strong>Category:</strong> ' + recipe.category + '</p>' +
                            '<p><strong>Ingredients:</strong></p>' +
                            ingredientsHtml +
                            '<p><strong>Instructions:</strong> ' + recipe.instructions + '</p>';

overlay.style.display = 'block';
}

function openOverlay2() {
    var overlay2 = document.getElementById('overlay2');
    overlay2.style.display = 'block';
}

function closeOverla2() {
    var overlay2 = document.getElementById('overlay2');
    overlay2.style.display = 'none';
}