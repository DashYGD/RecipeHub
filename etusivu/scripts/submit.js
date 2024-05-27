function addIngredient() {
    var ingredient = document.getElementById('ingredient').value;
    var quantity = document.getElementById('quantity').value;
    var unit = document.getElementById('unit').value;
    var price = document.getElementById('price').value;

    if (ingredient.trim() !== '' && quantity.trim() !== '' && unit.trim() !== '' && price.trim() !== '') {

        var ingredientDisplay = document.createElement('div');
        ingredientDisplay.textContent = 'Nimi: ' + ingredient + ' - Määrä: ' + quantity + ' ' + unit + ' - Hinta: ' + price + '€';
        ingredientDisplay.className = 'ready-ingredient';
        
        document.getElementById('ready-ingredients').appendChild(ingredientDisplay);

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

        var unitInput = document.createElement('input');
        unitInput.type = 'hidden';
        unitInput.name = 'unit[]';
        unitInput.value = unit;
        document.getElementById('add-recipe-form').appendChild(unitInput);

        var priceInput = document.createElement('input');
        priceInput.type = 'hidden';
        priceInput.name = 'price[]';
        priceInput.value = price;
        document.getElementById('add-recipe-form').appendChild(priceInput);

        document.getElementById('ingredient').value = '';
        document.getElementById('quantity').value = '';
        document.getElementById('unit').value = '';
        document.getElementById('price').value = '';
    }
}

function openIngredientPopup(ingredient, quantity, unit, price) {
    var ingredientDetails = document.getElementById('ingredient-details');
    ingredientDetails.innerHTML = '<p>Aineosat: ' + ingredient + '</p>' +
                                  '<p>Määrä: ' + quantity + ' ' + unit + '</p>' +
                                  '<p>Hinta: ' + price + '€</p>';
    document.getElementById('ingredient-popup').style.display = 'block';
}

function closeIngredientPopup() {
    document.getElementById('ingredient-popup').style.display = 'none';
}

document.getElementById("add-recipe-form").addEventListener("submit", function(event) {
    if (document.getElementsByClassName('ready-ingredient').length > 0) {
        event.preventDefault();

        var formData = new FormData(document.getElementById('add-recipe-form'));

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
            console.log(data); 
            alert('Recipe submitted successfully!');
            location.href = "/etusivu";
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            alert('There was an error submitting the recipe.');
        });
    } else {
        alert('Please add at least one ingredient with additional information before submitting the form.');
        event.preventDefault();
    }
});
