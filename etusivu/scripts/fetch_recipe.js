function updateSearchResults_1(results) {
    var searchResultsContainer = document.getElementById('search-results_1');
    searchResultsContainer.innerHTML = '';
  
    for (var i = 0; i < results.length; i++) {
      searchResultsContainer.style.display = "flex";
        var resultItem = document.createElement('div');
        resultItem.className = 'recipe-card';
        resultItem.name = i;
  
        resultItem.addEventListener('click', function() {
          openOverlay(results[resultItem.name]);
        });
  
  
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
  
  function openOverlay(recipe) {
    var overlay = document.getElementById('overlay');
    var overlayContent = document.getElementById('overlay-content');
  
    overlayContent.innerHTML = '';
  
    var ingredientsHtml = '';
    if (recipe.ingredients) {
        for (var j = 0; j < recipe.ingredients.length; j++) {
            ingredientsHtml += '<p>' + recipe.ingredients[j].name + ' (' + recipe.ingredients[j].quantity + ') - ' + recipe.ingredients[j].price + ' €</p>';
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
  
  function closeOverlay() {
    var overlay = document.getElementById('overlay');
    overlay.style.display = 'none';
  }
  
  function formatIngredients(ingredients) {
    var formatted = '';
    for (var i = 0; i < ingredients.length; i++) {
        formatted += '<p>' + ingredients[i].name + ' (' + ingredients[i].quantity + '), ';
    }
    formatted = formatted.slice(0, -2);
    return formatted;
  }
  
  var selectedCategory = ""; // Variable to store the selected category
  
  function filterRecipesByCategory(category) {
    selectedCategory = category; // Store the selected category
    searchRecipes(); // Perform search with the updated category
  }
  
  function searchRecipes() {
    var input = document.getElementById('search-input_1').value;
    var searchResultsContainer = document.getElementById('search-results_1');
  
    if (input.length >= 1) {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var results = JSON.parse(xhr.responseText);
          updateSearchResults_1(results);
        }
      };
  
      var url = 'server/recipe_query.php?query=' + input;
      if (selectedCategory !== undefined && selectedCategory !== "") {
        url += '&category=' + selectedCategory; // Append category to the URL if it's not undefined or empty
      }
      xhr.open('GET', url, true);
      xhr.send();
    } else {
      searchResultsContainer.innerHTML = '';
      searchResultsContainer.style.display = "none";
    }
    return true;
  }