


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

document.getElementById("add-recipe-form").addEventListener("submit", function(event) {
    // Check your condition here
    if (document.getElementById('ready-ingredient')) {
    var ingredientAdded = document.getElementById('ready-ingredient').value;
    console.log(ingredientAdded);
    }

    if (ingredientAdded !== 'true') {
        // Prevent form submission if condition is not met
        alert('Please add at least one ingredient with additional information before submitting the form.');
        event.preventDefault(); // Prevent form submission
    }
});


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

