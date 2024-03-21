


function selectEvent(selectedOption) {
    var selectedEventId = selectedOption.getAttribute('data-event-id');
    var selectedEventValue = selectedOption.getAttribute('name');
    var form_1 = document.getElementById('searchForm_1');

    document.getElementById('searchInput_2').value = selectedEventValue;
    localStorage.setItem('selectedEventId', selectedEventId);
    document.getElementById('submitButton').value = selectedEventId;

    localStorage.setItem('containerId', document.getElementById("tapahtumakalenteri_1").getAttribute('id'));

    form_1.submit();
}


function updateSearchResults_1(results) {
    var searchResultsContainer = document.getElementById('searchResultsContainer');
    searchResultsContainer.innerHTML = '';

    for (var i = 0; i < results.length; i++) {
        var resultItem = document.createElement('div');
        resultItem.innerHTML = '<div style="text-align: center;" class="scrollpos w3-bar-item w3-button" onclick="selectEvent(this)" name="' + results[i].otsikko + '" data-event-id="' + results[i].id + '">' + results[i].otsikko + '</div>';

        searchResultsContainer.appendChild(resultItem);
    }
}


function searchEvents(event) {
  
    var input = document.getElementById('searchInput_2').value;
    var searchResultsContainer = document.getElementById('searchResultsContainer');
    
  
    if (input.length >= 1) {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var results = JSON.parse(xhr.responseText);
          updateSearchResults_1(results);
        }
      };
  
      xhr.open('GET', 'kirjaudu/server/event_query.php?query=' + input, true);
      xhr.send();
    } else {
      searchResultsContainer.innerHTML = '';
    }
    return true;
  }