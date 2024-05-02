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
        
        // Append ingredient display to ready ingredients
        document.getElementById('ready-ingredients').appendChild(ingredientDisplay);

        // Create hidden input fields for ingredient, quantity, and price
        var ingredientInput = document.createElement('input');
        ingredientInput.type = 'hidden';
        ingredientInput.name = 'ingredient[]';
        ingredientInput.value = ingredient;
        document.getElementById('add-recipe-form').appendChild(ingredientInput);

        var quantityInput = document.createElement('input');
        quantityInput.type = 'hidden';
        quantityInput.name = 'quantity[]';
        quantityInput.value = quantity;
        document.getElementById('add-recipe-form').appendChild(quantityInput);

        var priceInput = document.createElement('input');
        priceInput.type = 'hidden';
        priceInput.name = 'price[]';
        priceInput.value = price;
        document.getElementById('add-recipe-form').appendChild(priceInput);
        
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



document.getElementById("add-recipe-form").addEventListener("submit", function(event) {
    if (document.getElementsByClassName('ready-ingredient')) {
        var formData = new FormData(document.getElementById('add-recipe-form'));

        // Send form data to the server using fetch
        fetch('server/add-recipe.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            console.log(data); // Log the response from the server
            // Optionally, display a success message to the user
            alert('Recipe submitted successfully!');
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            // Optionally, display an error message to the user
            alert('There was an error submitting the recipe.');
        });
    } else {
        var ingredientAdded = document.getElementsByClassName('ready-ingredient').value;
        console.log(ingredientAdded);
        
        // Prevent form submission if condition is not met
        alert('Please add at least one ingredient with additional information before submitting the form.');
        event.preventDefault(); // Prevent form submission
    }
});