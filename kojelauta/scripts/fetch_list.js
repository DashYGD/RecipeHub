document.addEventListener('DOMContentLoaded', function() {
    var basketButton = document.getElementById('shoppingBasketButton');
    var dropdown = document.getElementById('shopping-basket-dropdown');

    basketButton.addEventListener('click', function(event) {
        event.stopPropagation();
        dropdown.classList.toggle('show');
        
        if (dropdown.classList.contains('show')) {
            fetchShoppingBasket();
        }
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && !basketButton.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
});

function fetchShoppingBasket() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'server/fetch_list.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var results = JSON.parse(xhr.responseText);
            displayShoppingBasket(results);
        }
    };
    xhr.send();
}

function displayShoppingBasket(results) {
    var dropdown = document.getElementById('shopping-basket-dropdown');
    dropdown.innerHTML = ''; 
    
    // Create column containers
    var ingredientColumn = document.createElement('div');
    ingredientColumn.classList.add('column');
    var quantityColumn = document.createElement('div');
    quantityColumn.classList.add('column');
    var priceColumn = document.createElement('div');
    priceColumn.classList.add('column');

    var priceTitle = document.createElement('h3');
    priceTitle.textContent = 'Ingredients: Quantity: Price:';
    priceColumn.appendChild(priceTitle);

    // Populate columns with data
    results.forEach(function(recipe) {
        if (recipe.ingredients) {
            recipe.ingredients.forEach(function(ingredient) {
                // Create a row container
                var row = document.createElement('div');
                row.classList.add('row');
                
                // Ingredient name
                var ingredientName = document.createElement('p');
                ingredientName.textContent = ingredient.name;
                row.appendChild(ingredientName);
                ingredientColumn.appendChild(row);

                // Ingredient quantity
                var ingredientQuantity = document.createElement('input');
                ingredientQuantity.type = 'text';
                ingredientQuantity.value = ingredient.quantity;
                row.appendChild(ingredientQuantity);
                quantityColumn.appendChild(row);

                // Ingredient price
                var ingredientPrice = document.createElement('input');
                ingredientPrice.type = 'number';
                ingredientPrice.value = isNaN(ingredient.price) ? 'N/A' : parseFloat(ingredient.price).toFixed(2);
                row.appendChild(ingredientPrice);
                priceColumn.appendChild(row);
            });
        }
    });

    // Create a container for the columns
    var columnsContainer = document.createElement('div');
    columnsContainer.classList.add('columns-container');
    columnsContainer.appendChild(ingredientColumn);
    columnsContainer.appendChild(quantityColumn);
    columnsContainer.appendChild(priceColumn);
    
    dropdown.appendChild(columnsContainer);

    dropdown.classList.add('show');
}
