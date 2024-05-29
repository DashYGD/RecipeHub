function updateSearchResults_2(results) {
    var searchResultsContainer = document.getElementById('search-results_2');
    searchResultsContainer.innerHTML = '';
  
    for (var i = 0; i < results.length; i++) {
        var resultItem = document.createElement('div');
        resultItem.className = 'recipe-card_2';
        resultItem.id = 'recipe-card-' + i;
  
        (function(index) {
            resultItem.addEventListener('click', function() {
              openOverlay_6(results[index]);
            });
        })(i);
  
        var totalCost = 0;
        for (var j = 0; j < results[i].ingredients.length; j++) {
            totalCost += +results[i].ingredients[j].price;
        }
        totalCost = totalCost.toFixed(2);
        
        var imageUrl = results[i].image ? results[i].image : '';
        resultItem.innerHTML = '<img src="' + imageUrl + '" alt="' + results[i].name + '">' +
                               '<h2>' + results[i].name + '</h2>' +
                               '<p><strong>Kategoria:</strong> ' + (results[i].category ? results[i].category : '') + '</p>' +
                               '<p><strong>Hinta: </strong>' + totalCost + ' €</p>';
  
        searchResultsContainer.appendChild(resultItem);
    }
}
  
function openOverlay_6(recipe) {
    var overlay = document.getElementById('overlay_6');
    var overlayContent = document.getElementById('overlay-content_6');

    overlayContent.innerHTML = '';

    var ingredientsHtml = '';
    if (recipe.ingredients) {
        for (var j = 0; j < recipe.ingredients.length; j++) {
            var price = parseFloat(recipe.ingredients[j].price);
            ingredientsHtml += '<p>' + recipe.ingredients[j].name + ' (' + recipe.ingredients[j].quantity + ') - ' + (isNaN(price) ? 'N/A' : price.toFixed(2)) + ' €</p>';
        }
    }

    overlayContent.innerHTML = '<img id="recipe-image-display" src="' + recipe.image + '" alt="' + recipe.name + '">' +
                                '<h2 id="recipe-name">' + recipe.name + '</h2>' +
                                '<p><strong>Kategoria:</strong> <span id="recipe-category">' + recipe.category + '</span></p>' +
                                '<p><strong>Aineosat:</strong></p>' +
                                '<div id="recipe-ingredients">' + ingredientsHtml + '</div>' +
                                '<p><strong>Ohjeet:</strong> <span id="recipe-instructions">' + recipe.instructions + '</span></p><br>' +
                                '<button type="button" onclick=\'addToList(' + JSON.stringify(recipe) + ')\'>Lisää ostoskoriin</button>\n' +
                                '<button type="button" onclick=\'addFavorite(' + JSON.stringify(recipe) + ')\'>Lisää suosikkeihin</button>\n' +
                                '<button type="button" id="edit-button_1" onclick=\'editRecipe(' + JSON.stringify(recipe) + ')\'>Muokkaa reseptiä</button>\n' +
                                '<button type="button" onclick=\'deleteRecipe("' + recipe._id.$oid + '")\'>Poista resepti</button><br>';

    overlay.style.display = 'flex';
}

function editRecipe(recipe) {
    var overlayContent = document.getElementById('overlay-content_6');

    var editFormHtml = `
        <form id="edit-recipe-form" method="POST" enctype="multipart/form-data">
            <label for="recipe-name">Reseptin nimi:</label><br>
            <input type="text" id="recipe-name" name="recipe-name" value="${recipe.name}" required><br><br>

            <label for="category">Kategoria:</label><br>
            <select id="category" name="category" required>
                <option value="Aamiainen" ${recipe.category === 'Aamiainen' ? 'selected' : ''}>Aamiainen</option>
                <option value="lounas" ${recipe.category === 'lounas' ? 'selected' : ''}>Lounas</option>
                <option value="valipala" ${recipe.category === 'valipala' ? 'selected' : ''}>Välipala</option>
                <option value="paivallinen" ${recipe.category === 'paivallinen' ? 'selected' : ''}>Päivällinen</option>
                <option value="iltapala" ${recipe.category === 'iltapala' ? 'selected' : ''}>Iltapala</option>
            </select><br><br>

            <label for="ingredient">Ainesosa:</label><br>
            <input type="text" id="ingredient" name="ingredient"><br><br>

            <label for="quantity">Määrä:</label><br>
            <input type="number" id="quantity" name="quantity"><br><br>

            <label for="unit">Yksikkö:</label><br>
            <select id="unit" name="unit">
                <option value="g">g</option>
                <option value="kg">kg</option>
                <option value="ml">ml</option>
                <option value="l">l</option>
                <option value="kpl">kpl</option>
            </select><br><br>

            <label for="price">Hinta:</label><br>
            <input type="number" id="price" name="price"><br><br>

            <button type="button" onclick="addIngredient()">Tallenna ainesosa</button><br><br>

            <div id="ready-ingredients">
                ${recipe.ingredients.map(ingredient => `
                    <div class="ingredient-item">
                        <input type="text" class="ingredient-name" value="${ingredient.name}">
                        <input type="number" class="ingredient-quantity" value="${ingredient.quantity}">
                        <select class="ingredient-unit">
                            <option value="g" ${ingredient.unit === 'g' ? 'selected' : ''}>g</option>
                            <option value="kg" ${ingredient.unit === 'kg' ? 'selected' : ''}>kg</option>
                            <option value="ml" ${ingredient.unit === 'ml' ? 'selected' : ''}>ml</option>
                            <option value="l" ${ingredient.unit === 'l' ? 'selected' : ''}>l</option>
                            <option value="kpl" ${ingredient.unit === 'kpl' ? 'selected' : ''}>kpl</option>
                        </select>
                        <input type="number" class="ingredient-price" value="${ingredient.price}">
                        <button type="button" onclick="removeIngredient(this)">Poista</button>
                    </div>`).join('')}
            </div>

            <label for="image">Kuva:</label><br>
            <input type="file" id="image" name="image" accept="image/*"><br><br>

            <label for="instructions">Ohjeet:</label><br>
            <textarea id="instructions" name="instructions" rows="10" cols="50" required>${recipe.instructions}</textarea><br><br>

            <button type="button" onclick="saveRecipeChanges('${recipe._id.$oid}')">Tallenna muutokset</button>
        </form>
    `;

    overlayContent.innerHTML = editFormHtml;
}

function addIngredient() {
    var ingredientDiv = document.createElement('div');
    ingredientDiv.className = 'ingredient-item';
    ingredientDiv.innerHTML = `
        <input type="text" class="ingredient-name" placeholder="Nimi">
        <input type="number" class="ingredient-quantity" placeholder="Määrä">
        <select class="ingredient-unit">
            <option value="g">g</option>
            <option value="kg">kg</option>
            <option value="ml">ml</option>
            <option value="l">l</option>
            <option value="kpl">kpl</option>
        </select>
        <input type="number" class="ingredient-price" placeholder="Hinta">
        <button type="button" onclick="removeIngredient(this)">Poista</button>
    `;
    document.getElementById('ready-ingredients').appendChild(ingredientDiv);
}

function removeIngredient(button) {
    button.parentElement.remove();
}

function saveRecipeChanges(recipeId) {
    var updatedRecipe = {
        name: document.getElementById('recipe-name').value,
        category: document.getElementById('category').value,
        instructions: document.getElementById('instructions').value,
        ingredients: []
    };

    var ingredientItems = document.getElementsByClassName('ingredient-item');
    for (var i = 0; i < ingredientItems.length; i++) {
        var ingredient = {
            name: ingredientItems[i].getElementsByClassName('ingredient-name')[0].value,
            quantity: ingredientItems[i].getElementsByClassName('ingredient-quantity')[0].value,
            unit: ingredientItems[i].getElementsByClassName('ingredient-unit')[0].value,
            price: ingredientItems[i].getElementsByClassName('ingredient-price')[0].value
        };
        updatedRecipe.ingredients.push(ingredient);
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'server/update_recipe.php', true);

    var formData = new FormData();
    formData.append('recipe', JSON.stringify(updatedRecipe));
    var imageFile = document.getElementById('image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    formData.append('recipeId', recipeId);

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert('Resepti päivitetty onnistuneesti');
                    window.location.href = '../etusivu';
                } else {
                    alert('Virhe päivitettäessä reseptiä: ' + response.message);
                }
            } else {
                alert('Virhe päivitettäessä reseptiä: Palvelinvirhe');
            }
        }
    };
    xhr.send(formData);
}






function addFavorite(recipe) {
    delete recipe._id;
    delete recipe.owner;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'server/add_favorite.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert(response.message);
                window.location.href = '../etusivu';
            } else {
                alert('Error: ' + response.message);
            }
        } else if (xhr.readyState == 4) {
            alert('Server error: ' + xhr.status);
        }
    };

    xhr.send(JSON.stringify(recipe));
}


function addToList(recipe) {
    delete recipe._id;
    delete recipe.category;
    delete recipe.image;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'server/add_to_list.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    alert('Ingredients added to list');
                    window.location.href = '../etusivu';
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('Error adding to list: Server Error');
            }
        }
    };

    xhr.send(JSON.stringify(recipe));
}


function deleteRecipe(recipeId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'server/delete_recipe.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert('Recipe deleted successfully');
                    window.location.href = '../etusivu';
                } else {
                    alert('Error deleting recipe: ' + response.message);
                }
            } else {
                alert('Error deleting recipe: Server Error');
            }
        }
    };

    xhr.send(JSON.stringify({ recipe_id: recipeId }));
}










function formatIngredients(ingredients) {
var formatted = '';
for (var i = 0; i < ingredients.length; i++) {
    formatted += '<p>' + ingredients[i].name + ' (' + ingredients[i].quantity + '), ';
}
formatted = formatted.slice(0, -2);
return formatted;
}

function searchRecipes_2() {
var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function () {
if (xhr.readyState == 4 && xhr.status == 200) {
    var results = JSON.parse(xhr.responseText);
    console.log("ok");
    updateSearchResults_2(results);
}
};

var url = 'server/recipe_query_2.php';
xhr.open('GET', url, true);
xhr.send();
return true;
}




function updateSearchResults_3(results) {
    var searchResultsContainer = document.getElementById('search-results_3');
    searchResultsContainer.innerHTML = '';
  
    for (var i = 0; i < results.length; i++) {
        var resultItem = document.createElement('div');
        resultItem.className = 'recipe-card_3';
        resultItem.id = 'recipe-card-1' + i;
        //console.log(resultItem.name);
  
        (function(index) {
            resultItem.addEventListener('click', function() {
              openOverlay_7(results[index]);
            });
          })(i);
  
        var totalCost = 0;
        for (var j = 0; j < results[i].ingredients.length; j++) {
            totalCost += +results[i].ingredients[j].price;
        }
        totalCost = totalCost.toFixed(2)
        
        var imageUrl = results[i].image ? results[i].image : ''; //Tarkistaa onko kuvaa
        resultItem.innerHTML = '<img src="' + imageUrl + '" alt="' + results[i].name + '">' +
                               '<h2>' + results[i].name + '</h2>' +
                               '<p><strong>Kategoria:</strong> ' + (results[i].category ? results[i].category : '') + '</p>' +
                               '<p><strong>Hinta: </strong>' + totalCost + ' €</p>';
  
        searchResultsContainer.appendChild(resultItem);
    }
  }


function openOverlay_7(recipe) {
    var overlay = document.getElementById('overlay_7');
    var overlayContent = document.getElementById('overlay-content_7');
    console.log(recipe._id);

    overlayContent.innerHTML = '';

    var ingredientsHtml = '';
    if (recipe.ingredients) {
        for (var j = 0; j < recipe.ingredients.length; j++) {
            var price = parseFloat(recipe.ingredients[j].price);
            ingredientsHtml += '<p>' + recipe.ingredients[j].name + ' (' + recipe.ingredients[j].quantity + ') - ' + (isNaN(price) ? 'N/A' : price.toFixed(2)) + ' €</p>';
        }
    }

    overlayContent.innerHTML = '<img src="' + recipe.image + '" alt="' + recipe.name + '">' +
                                '<h2>' + recipe.name + '</h2>' +
                                '<p><strong>Kategoria:</strong> ' + recipe.category + '</p>' +
                                '<p><strong>Aineosat:</strong></p>' +
                                ingredientsHtml +
                                '<p><strong>Ohjeet:</strong> ' + recipe.instructions + '</p><br>' +
                                '<button type="button" onclick=\'addToList(' + JSON.stringify(recipe) + ')\'>Lisää ostoskoriin</button>\n' +
                                '<button type="button" onclick=\'deleteFavorite("' + recipe._id.$oid + '")\'>Poista suosikeista</button><br>';

    overlay.style.display = 'flex';
}


function searchRecipes_3() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
        var results = JSON.parse(xhr.responseText);
        console.log("ok");
        updateSearchResults_3(results);
    }
    };
    
    var url = 'server/recipe_query_3.php';
    xhr.open('GET', url, true);
    xhr.send();
    return true;
    }

    function deleteFavorite(recipeId) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'server/delete_favorite.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
    
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('Recipe deleted successfully');
                        window.location.href = '../etusivu';
                    } else {
                        alert('Error deleting recipe: ' + response.message);
                    }
                } else {
                    alert('Error deleting recipe: Server Error');
                }
            }
        };
    
        xhr.send(JSON.stringify({ recipe_id: recipeId }));
    }