function toggleDropdown() {
    var dropdown = document.getElementById("categoryDropdown");
    dropdown.classList.toggle("show");
}

function filterRecipesByCategory(category) {
    // Implement filtering logic here based on the selected category
    console.log("Filter recipes by category:", category);
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdown = document.getElementById("categoryDropdown");
        if (dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    }
}
