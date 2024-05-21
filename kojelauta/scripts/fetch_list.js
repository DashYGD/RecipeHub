document.addEventListener('DOMContentLoaded', function() {
    var basketButton = document.getElementById('shoppingBasketButton');
    var dropdown = document.getElementById('shopping-basket-dropdown');
    var body = document.body;

    basketButton.addEventListener('click', function(event) {
        event.stopPropagation();
        dropdown.classList.toggle('show');
        
        if (dropdown.classList.contains('show')) {
            fetchShoppingBasket();
            body.classList.add('noscroll'); // Disable scrolling on main page
        } else {
            body.classList.remove('noscroll'); // Enable scrolling on main page
        }
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && !basketButton.contains(event.target)) {
            dropdown.classList.remove('show');
            body.classList.remove('noscroll'); // Enable scrolling on main page
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

    var ingredientColumn = document.createElement('div');
    ingredientColumn.classList.add('column');
    var quantityColumn = document.createElement('div');
    quantityColumn.classList.add('column');
    var unitColumn = document.createElement('div');
    unitColumn.classList.add('column');
    var priceColumn = document.createElement('div');
    priceColumn.classList.add('column');
    var actionColumn = document.createElement('div');
    actionColumn.classList.add('column');

    var ingredientTitle = document.createElement('h3');
    ingredientTitle.textContent = 'Ingredients:';
    ingredientColumn.appendChild(ingredientTitle);

    var quantityTitle = document.createElement('h3');
    quantityTitle.textContent = 'Quantity:';
    quantityColumn.appendChild(quantityTitle);

    var unitTitle = document.createElement('h3');
    unitTitle.textContent = 'Unit:';
    unitColumn.appendChild(unitTitle);

    var priceTitle = document.createElement('h3');
    priceTitle.textContent = 'Price:';
    priceColumn.appendChild(priceTitle);

    var actionTitle = document.createElement('h3');
    actionTitle.textContent = 'Action:';
    actionColumn.appendChild(actionTitle);

    var rowsContainer = document.createElement('div');
    rowsContainer.classList.add('rows-container');

    results.forEach(function(recipe) {
        if (recipe.ingredients) {
            recipe.ingredients.forEach(function(ingredient) {
                var row = document.createElement('div');
                row.classList.add('row');

                var ingredientName = document.createElement('span');
                ingredientName.textContent = ingredient.name;
                row.appendChild(ingredientName);

                var ingredientQuantity = document.createElement('span');
                ingredientQuantity.textContent = ingredient.quantity;
                row.appendChild(ingredientQuantity);

                var ingredientUnit = document.createElement('span');
                ingredientUnit.textContent = ingredient.unit;
                row.appendChild(ingredientUnit);

                var ingredientPrice = document.createElement('span');
                ingredientPrice.textContent = ingredient.price;
                row.appendChild(ingredientPrice);

                var editButton = document.createElement('button');
                editButton.textContent = 'Edit';
                editButton.addEventListener('click', function() {
                    row.classList.add('edit-mode');
                });
                row.appendChild(editButton);

                var saveButton = document.createElement('button');
                saveButton.textContent = 'Save';
                saveButton.style.display = 'none';
                saveButton.addEventListener('click', function() {
                    row.classList.remove('edit-mode');
                });
                row.appendChild(saveButton);

                var deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.addEventListener('click', function() {
                    // Implement delete functionality here
                });
                row.appendChild(deleteButton);

                rowsContainer.appendChild(row);
            });
        }
    });

    var columnsContainer = document.createElement('div');
    columnsContainer.classList.add('columns-container');
    columnsContainer.appendChild(ingredientColumn);
    columnsContainer.appendChild(quantityColumn);
    columnsContainer.appendChild(unitColumn);
    columnsContainer.appendChild(priceColumn);
    columnsContainer.appendChild(actionColumn);

    dropdown.appendChild(columnsContainer);
    dropdown.appendChild(rowsContainer); // Append the rowsContainer to display the rows
    dropdown.classList.add('show');
}



function deleteIngredient(id, row) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'server/delete_ingredient.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Remove the row from the DOM
            row.parentNode.removeChild(row);
        }
    };
    xhr.send('id=' + id);
}
