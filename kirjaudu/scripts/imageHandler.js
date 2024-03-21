function selectImage(selectedOption) {
    var selectedImageId = selectedOption.getAttribute('data-image-id');
    var selectedImageValue = selectedOption.getAttribute('name');
    var form_1 = document.getElementById('searchForm_2');

    document.getElementById('searchInput_3').value = selectedImageValue;
    localStorage.setItem('selectedImageId', selectedImageId);
    document.getElementById('submitButton_2').value = selectedImageId;

    localStorage.setItem('containerId', document.getElementById("kuvagalleria_1").getAttribute('id'));

    form_1.submit();
}


function updateSearchResults_2(results) {
    var searchResultsContainer = document.getElementById('searchResultsContainer_2');
    searchResultsContainer.innerHTML = '';

    for (var i = 0; i < results.length; i++) {
        var resultItem = document.createElement('div');
        resultItem.innerHTML = '<div style="text-align: center;" class="scrollpos w3-bar-item w3-button" onclick="selectImage(this)" name="' + results[i].kuva_otsikko + '" data-image-id="' + results[i].id + '">' + results[i].kuva_otsikko + '</div>';

        searchResultsContainer.appendChild(resultItem);
    }
}


function searchImages(event) {
  
    var input = document.getElementById('searchInput_3').value;
    var searchResultsContainer = document.getElementById('searchResultsContainer_2');
    
  
    if (input.length >= 1) {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var results = JSON.parse(xhr.responseText);
          updateSearchResults_2(results);
        }
      };
  
      xhr.open('GET', 'kirjaudu/server/image_query.php?query=' + input, true);
      xhr.send();
    } else {
      searchResultsContainer.innerHTML = '';
    }
    return true;
  }