function updateSearchResults_1(results) {
  var searchResultsContainer = document.getElementById('search-results_1');
  searchResultsContainer.innerHTML = '';

  for (var i = 0; i < results.length; i++) {
      searchResultsContainer.style.display = "flex";
      var resultItem = document.createElement('div');
      resultItem.className = 'recipe-card';
      resultItem.name = i;
      
      (function(item) {
          resultItem.addEventListener('click', function() {
              openOverlay_1(results[item]);
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

  
function openOverlay_1(recipe) {
  var overlay = document.getElementById('overlay_1');
  var overlayContent = document.getElementById('overlay-content_1');

  overlayContent.innerHTML = '';

  var ingredientsHtml = '';
  if (recipe.ingredients) {
      for (var j = 0; j < recipe.ingredients.length; j++) {
          ingredientsHtml += '<p>' + recipe.ingredients[j].name + ' (' + recipe.ingredients[j].quantity + ' ' + recipe.ingredients[j].unit + ') - ' + recipe.ingredients[j].price + ' €</p>';
      }
  }

  overlayContent.innerHTML = '<img src="' + recipe.image + '" alt="' + recipe.name + '">' +
                              '<h2>' + recipe.name + '</h2>' +
                              '<p><strong>Kategoria:</strong> ' + recipe.category + '</p>' +
                              '<p><strong>Ainesosat:</strong></p>' +
                              ingredientsHtml +
                              '<p><strong>Ohjeet:</strong> ' + recipe.instructions + '</p>';

  overlay.style.display = 'block';
}
  
  function formatIngredients(ingredients) {
    var formatted = '';
    for (var i = 0; i < ingredients.length; i++) {
        formatted += '<p>' + ingredients[i].name + ' (' + ingredients[i].quantity + '), ';
    }
    formatted = formatted.slice(0, -2);
    return formatted;
  }
  
  var selectedCategory = "";
  
  function filterRecipesByCategory(category) {
    selectedCategory = category;
    searchRecipes_1();
  }
  
  function searchRecipes_1() {
    var input = '';
    input = document.getElementById('search-input_1').value;
    var searchResultsContainer = document.getElementById('search-results_1');
  
    if (input.length >= 0) {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var results = JSON.parse(xhr.responseText);
          updateSearchResults_1(results);
        }
      };
  
      var url = 'server/recipe_query_1.php?query=' + input;
      if (selectedCategory !== undefined && selectedCategory !== "") {
        url += '&category=' + selectedCategory;
      }
      xhr.open('GET', url, true);
      xhr.send();
    } else {
      searchResultsContainer.innerHTML = '';
      searchResultsContainer.style.display = "none";
    }
    return true;
  }