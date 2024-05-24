document.addEventListener('DOMContentLoaded', function() {
    var basketButton = document.getElementById('shoppingBasketButton');
    var dropdown = document.getElementById('shopping-basket-dropdown');
    var body = document.body;

    basketButton.addEventListener('click', function(event) {
        event.stopPropagation();
        dropdown.classList.toggle('show');
        
        if (dropdown.classList.contains('show')) {
            fetchShoppingBasket();
            body.classList.add('noscroll'); 
        } else {
            body.classList.remove('noscroll'); 
        }
    });

    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && !basketButton.contains(event.target)) {
            dropdown.classList.remove('show');
            body.classList.remove('noscroll'); 
        }
    });
});

function fetchShoppingBasket() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'server/fetch_list.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var results = JSON.parse(xhr.responseText);
            console.log(results);
            displayShoppingBasket(results);
        }
    };
    xhr.send();
}

function displayShoppingBasket(results) {
    var dropdown = document.getElementById('shopping-basket-dropdown');
    dropdown.innerHTML = '';

    var owner_id = '';
    var combinedIngredients = {};

    results.forEach(function(recipe) {
        owner_id = recipe.owner;
        if (recipe.ingredients) {
            recipe.ingredients.forEach(function(ingredient) {
                var key = ingredient.name + ingredient.unit;
                if (!combinedIngredients[key]) {
                    combinedIngredients[key] = {
                        name: ingredient.name,
                        quantity: 0,
                        unit: ingredient.unit,
                        price: 0
                    };
                }
                combinedIngredients[key].quantity += parseFloat(ingredient.quantity);
                combinedIngredients[key].price += parseFloat(ingredient.price);
            });
        }
    });

    if (Object.keys(combinedIngredients).length === 0) {
        var emptyMessage = document.createElement('p');
        emptyMessage.textContent = 'Empty';
        dropdown.appendChild(emptyMessage);
        dropdown.classList.add('show');
        return;
    }

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

    Object.values(combinedIngredients).forEach(function(ingredient) {
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
        ingredientPrice.textContent = ingredient.price.toFixed(2);
        row.appendChild(ingredientPrice);

        var editButton = document.createElement('button');
        editButton.textContent = 'Edit';
        editButton.addEventListener('click', function() {
            if (editButton.textContent === 'Edit') {
                editButton.textContent = 'Save';
                ingredientName.innerHTML = `<input type="text" value="${ingredient.name}">`;
                ingredientQuantity.innerHTML = `<input type="number" value="${ingredient.quantity}">`;
                var unitSelect = document.createElement('select');
                ['g', 'kg', 'ml', 'l', 'pcs'].forEach(function(option) {
                    var optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.textContent = option;
                    if (option === ingredient.unit) {
                        optionElement.selected = true;
                    }
                    unitSelect.appendChild(optionElement);
                });
                ingredientUnit.innerHTML = '';
                ingredientUnit.appendChild(unitSelect);
                ingredientPrice.innerHTML = `<input type="number" value="${ingredient.price}">`;
            } else {
                editButton.textContent = 'Edit';
                var updatedIngredient = {
                    name: ingredientName.querySelector('input').value,
                    quantity: ingredientQuantity.querySelector('input').value,
                    unit: ingredientUnit.querySelector('select').value,
                    price: ingredientPrice.querySelector('input').value
                };

                // Check if there are changes
                if (
                    ingredient.name !== updatedIngredient.name ||
                    ingredient.quantity !== updatedIngredient.quantity ||
                    ingredient.unit !== updatedIngredient.unit ||
                    ingredient.price !== updatedIngredient.price
                ) {
                    updateIngredient(owner_id, ingredient.name, updatedIngredient);
                }

                // Update the displayed values
                ingredient.name = updatedIngredient.name;
                ingredient.quantity = updatedIngredient.quantity;
                ingredient.unit = updatedIngredient.unit;
                ingredient.price = updatedIngredient.price;
                ingredientName.textContent = ingredient.name;
                ingredientQuantity.textContent = ingredient.quantity;
                ingredientUnit.textContent = ingredient.unit;
                ingredientPrice.textContent = parseFloat(ingredient.price).toFixed(2);
            }
        });

        var deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.addEventListener('click', function() {
            deleteIngredient(owner_id, ingredient.name);
        });

        var shareButton = document.createElement('button');
        shareButton.textContent = 'Share';
        shareButton.addEventListener('click', function() {
            var receiptContent = `Tuote: ${ingredient.name}\nMäärä: ${ingredient.quantity}${ingredient.unit}\nHinta arvio: ${ingredient.price}€\n`;
            var blob = new Blob([receiptContent], { type: 'text/plain' });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = `${ingredient.name}-receipt.txt`;
            a.click();
            URL.revokeObjectURL(url);
        });

        ingredientColumn.appendChild(ingredientName);
        quantityColumn.appendChild(ingredientQuantity);
        unitColumn.appendChild(ingredientUnit);
        priceColumn.appendChild(ingredientPrice);
        var actionRow = document.createElement('div');
        actionRow.classList.add('row');
        actionRow.appendChild(editButton);
        actionRow.appendChild(deleteButton);
        actionRow.appendChild(shareButton);
        actionColumn.appendChild(actionRow);
    });

    var columnsContainer = document.createElement('div');
    columnsContainer.classList.add('columns-container');
    columnsContainer.appendChild(ingredientColumn);
    columnsContainer.appendChild(quantityColumn);
    columnsContainer.appendChild(unitColumn);
    columnsContainer.appendChild(priceColumn);
    columnsContainer.appendChild(actionColumn);

    dropdown.appendChild(columnsContainer);
    dropdown.classList.add('show');
}

function updateIngredient(owner, oldName, updatedIngredient) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'server/update_ingredient.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                fetchShoppingBasket();
                console.log('Ingredient updated successfully');
            } else {
                alert(response.message);
            }
        }
    };

    var data = {
        owner: owner,
        oldName: oldName,
        name: updatedIngredient.name,
        quantity: updatedIngredient.quantity,
        unit: updatedIngredient.unit,
        price: updatedIngredient.price
    };
    
    xhr.send(JSON.stringify(data));
}


function deleteIngredient(owner, ingredientName) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'server/delete_ingredient.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                fetchShoppingBasket();
            } else {
                alert(response.message);
            }
        }
    };

    xhr.send(JSON.stringify({ owner: owner, name: ingredientName }));
}
