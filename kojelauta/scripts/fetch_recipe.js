function openOverlay(recipe) {
    var overlay = document.getElementById('overlay');
    var overlayContent = document.getElementById('overlay-content');
  
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