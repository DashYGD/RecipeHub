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

// JavaScript
// Function to add a new ingredient
function addIngredient() {
    var ingredient = document.getElementById('ingredient').value;
    var quantity = document.getElementById('quantity').value;
    var price = document.getElementById('price').value;

    if (ingredient.trim() !== '' && quantity.trim() !== '' && price.trim() !== '') {
        // Create a new element to display the ingredient
        var ingredientDisplay = document.createElement('div');
        ingredientDisplay.textContent = ingredient + ' - Quantity: ' + quantity + ', Price: ' + price;
        ingredientDisplay.className = 'ready-ingredient';
        
        // Attach click event to display ingredient details
        ingredientDisplay.addEventListener('click', function() {
            openIngredientPopup(ingredient, quantity, price);
        });
        
        // Append ingredient display to ready ingredients
        document.getElementById('ready-ingredients').appendChild(ingredientDisplay);
        
        // Clear input fields
        document.getElementById('ingredient').value = '';
        document.getElementById('quantity').value = '';
        document.getElementById('price').value = '';
    }
}

// Function to open the ingredient popup with additional details
function openIngredientPopup(ingredient, quantity, price) {
    var ingredientDetails = document.getElementById('ingredient-details');
    ingredientDetails.innerHTML = '<p>Ingredient: ' + ingredient + '</p>' +
                                  '<p>Quantity: ' + quantity + '</p>' +
                                  '<p>Price: ' + price + '</p>';
    document.getElementById('ingredient-popup').style.display = 'block';
}

// Function to close the ingredient popup
function closeIngredientPopup() {
    document.getElementById('ingredient-popup').style.display = 'none';
}

