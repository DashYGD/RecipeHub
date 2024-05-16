document.addEventListener('DOMContentLoaded', function() {
    var basketButton = document.getElementById('shoppingBasketButton');
    var dropdown = document.getElementById('shopping-basket-dropdown');

    // Toggle dropdown visibility when clicking the basket button
    basketButton.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent the click event from bubbling up
        dropdown.classList.toggle('show');
        
        // Fetch and display shopping basket items when opening the dropdown
        if (dropdown.classList.contains('show')) {
            fetchShoppingBasket();
        }
    });

    // Close the dropdown when clicking outside of it
    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target) && !basketButton.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
});

// Fetch shopping basket items from the server
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

// Function to display the shopping basket contents
function displayShoppingBasket(results) {
    var dropdown = document.getElementById('shopping-basket-dropdown');
    dropdown.innerHTML = ''; // Clear previous content
    
    var allIngredientsHtml = ''; // Variable to store all ingredients HTML

    results.forEach(function(recipe) {
        if (recipe.ingredients) {
            recipe.ingredients.forEach(function(ingredient) {
                allIngredientsHtml += '<p>' + ingredient.name + ' (' + ingredient.quantity + ') - ' + (isNaN(ingredient.price) ? 'N/A' : parseFloat(ingredient.price).toFixed(2)) + ' €</p>';
            });
        }
    });
    
    // Create one div to contain all ingredients
    var ingredientsContainer = document.createElement('div');
    ingredientsContainer.classList.add('recipe-item', 'w3-card', 'w3-container');
    ingredientsContainer.innerHTML = allIngredientsHtml;
    
    dropdown.appendChild(ingredientsContainer);

    dropdown.classList.add('show'); // Show the dropdown
}
