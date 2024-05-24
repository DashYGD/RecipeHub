var x = 0;
function openFilter() {
    dropdown = document.getElementById("filter-dropdownContainer");
    if (x == 0) {
        dropdown.style.display = "block";
        x = 1;
    } else {
        dropdown.style.display = "none";
        x = 0;
    }
}
