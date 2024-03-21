


function selectBullet(selectedOption) {
    var selectedBulletId = selectedOption.getAttribute('data-bullet-id');
    var selectedBulletValue = selectedOption.getAttribute('name');
    var form_1 = document.getElementById('searchForm_0');

    document.getElementById('searchInput_0').value = selectedBulletValue;
    localStorage.setItem('selectedBulletId', selectedBulletId);
    document.getElementById('submitButton_0').value = selectedBulletId;

    localStorage.setItem('containerId', document.getElementById("etusivu_1").getAttribute('id'));

    form_1.submit();
}


function updateSearchResults_0(results) {
    var searchResultsContainer = document.getElementById('searchResultsContainer_0');
    searchResultsContainer.innerHTML = '';

    for (var i = 0; i < results.length; i++) {
        var resultItem = document.createElement('div');
        resultItem.innerHTML = '<div style="text-align: center;" class="scrollpos w3-bar-item w3-button" onclick="selectBullet(this)" name="' + results[i].otsikko + '" data-bullet-id="' + results[i].id + '">' + results[i].otsikko + '</div>';

        searchResultsContainer.appendChild(resultItem);
    }
}


function searchBullets(event) {
  
    var input = document.getElementById('searchInput_0').value;
    var searchResultsContainer = document.getElementById('searchResultsContainer_0');
    
  
    if (input.length >= 1) {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var results = JSON.parse(xhr.responseText);
          updateSearchResults_0(results);
        }
      };
  
      xhr.open('GET', 'kirjaudu/server/bullet_query.php?query=' + input, true);
      xhr.send();
    } else {
      searchResultsContainer.innerHTML = '';
    }
    return true;
  }