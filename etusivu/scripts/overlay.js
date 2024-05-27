function updateSearchResults_2(results) {
    var searchResultsContainer = document.getElementById('search-results_2');
    searchResultsContainer.innerHTML = '';
  
    for (var i = 0; i < results.length; i++) {
        var resultItem = document.createElement('div');
        resultItem.className = 'recipe-card_2';
        resultItem.id = 'recipe-card-' + i;
        //console.log(resultItem.name);
  
        (function(index) {
            resultItem.addEventListener('click', function() {
              openOverlay_6(results[index]);
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


function openOverlay_6(recipe) {
    var overlay = document.getElementById('overlay_6');
    var overlayContent = document.getElementById('overlay-content_6');
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
                                '<button type="button" onclick=\'addFavorite(' + JSON.stringify(recipe) + ')\'>Lisää suosikkeihin</button>\n' +
                                '<button type="button" onclick=\'deleteRecipe("' + recipe._id.$oid + '")\'>Poista resepti</button><br>';

    overlay.style.display = 'flex';
}

function addFavorite(recipe) {
    delete recipe._id;
    delete recipe.owner;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'server/add_favorite.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert('Recipe added to Favorites');
            window.location.href = '../etusivu';
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
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert('Recipe added to basket');
            window.location.href = '../etusivu';
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
                                '<button type="button" onclick=\'addFavorite(' + JSON.stringify(recipe) + ')\'>Poista suosikeista</button><br>';

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