function updateSearchResults_1(results) {
    var searchResultsContainer = document.getElementById('search-results_1');
    searchResultsContainer.innerHTML = '';
  
    for (var i = 0; i < results.length; i++) {
        var resultItem = document.createElement('div');
        resultItem.className = 'recipe-card';
        resultItem.id = 'recipe-card-' + i;
        //console.log(resultItem.name);
  
        (function(index) {
            resultItem.addEventListener('click', function() {
              openOverlay1(results[index]);
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
                               '<p><strong>Category:</strong> ' + (results[i].category ? results[i].category : '') + '</p>' +
                               '<p><strong>Total Cost: </strong>' + totalCost + ' €</p>';
  
        searchResultsContainer.appendChild(resultItem);
    }
  }


  function openOverlay1(recipe) {
    var overlay = document.getElementById('overlay3');
    var overlayContent = document.getElementById('overlay-content3');
    console.log(recipe);

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
                                '<p><strong>Category:</strong> ' + recipe.category + '</p>' +
                                '<p><strong>Ingredients:</strong></p>' +
                                ingredientsHtml +
                                '<p><strong>Instructions:</strong> ' + recipe.instructions + '</p><br>' +
                                '<button type="button" onclick=\'addToList(' + JSON.stringify(recipe) + ')\'>Add to Basket</button><br>';

    overlay.style.display = 'block';
}

function addToList(recipe) {
    // Remove the _id field if it exists
    delete recipe._id;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'server/add_to_list.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert('Recipe added to basket');
        }
    };

    xhr.send(JSON.stringify(recipe));
}



function formatIngredients(ingredients) {
var formatted = '';
for (var i = 0; i < ingredients.length; i++) {
    formatted += '<p>' + ingredients[i].name + ' (' + ingredients[i].quantity + '), ';
}
formatted = formatted.slice(0, -2);
return formatted;
}

window.onload = function() {
    searchRecipes();
};
  
function searchRecipes() {
var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function () {
if (xhr.readyState == 4 && xhr.status == 200) {
    var results = JSON.parse(xhr.responseText);
    updateSearchResults_1(results);
}
};

var url = 'server/recipe_query.php';
xhr.open('GET', url, true);
xhr.send();
return true;
}

function openOverlay2() {
    var overlay2 = document.getElementById('overlay2');
    overlay2.style.display = 'block';
}

function closeOverlay2() {
    var overlay2 = document.getElementById('overlay2');
    overlay2.style.display = 'none';
}

function closeOverlay3() {
    var overlay3 = document.getElementById('overlay3');
    overlay3.style.display = 'none';
}