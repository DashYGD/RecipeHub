
document.addEventListener("DOMContentLoaded", function () {
    const imageUpdateForms = document.querySelectorAll(".image-update-form");

    imageUpdateForms.forEach(form => {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(form);

            fetch("kirjaudu/server/imageHandler.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (document.getElementById('image-display')) {
                    document.getElementById('image-display').src = data;
                }

                alert(data);
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    });


    const eventUpdateForms = document.querySelectorAll(".event-update-form");

    eventUpdateForms.forEach(form => {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(form);

            fetch("kirjaudu/server/eventHandler.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    });

    const bulletUpdateForms = document.querySelectorAll(".bullet-update-form");

    bulletUpdateForms.forEach(form => {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(form);

            fetch("kirjaudu/server/bulletHandler.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    });

    var xhr = new XMLHttpRequest();
xhr.open('GET', 'kirjaudu/server/fetchPrevious.php', true);
xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
        var responseData = JSON.parse(xhr.responseText);
        
        var imageContainer = document.getElementById('previous-image-container');
        if (imageContainer && responseData.kuvagalleria) {
            var imageData = responseData.kuvagalleria;
            imageContainer.innerHTML = '<div class="w3-card-4 w3-container w3-white" style="width:50%; overflow:hidden;"><center>' +
                                        '<h2><b>' + imageData.kuva_otsikko + '</b></h2>' +
                                        '<img src="' + imageData.kuva + '" alt="' + imageData.kuva_tietoa + '" style="width:100%;">' +
                                        '<p>' + imageData.kuva_tietoa + '</p>' +
                                        '</center></div>';
        }

        var bulletContainer = document.getElementById('previous-bullet-container');
        if (bulletContainer && responseData.tiedotteet) {
            var bulletData = responseData.tiedotteet;
            bulletContainer.innerHTML = '<div class="w3-card-4 w3-container w3-white" style="width:50%; overflow:hidden;">' +
                                        '<p>' + bulletData.pvm + '</p>' +
                                        '<center><h2><b>' + bulletData.otsikko + '</b></h2>' +
                                        '<p>' + bulletData.teksti + '<br></p>' +
                                        '</center></div>';
        }
        
        var eventContainer = document.getElementById('previous-event-container');
        if (eventContainer && responseData.tapahtumakalenteri) {
            var eventData = responseData.tapahtumakalenteri;
            eventContainer.innerHTML = '<div class="w3-card-4 w3-container w3-white" style="width:50%; overflow:hidden;">' +
                                        '<p>' + eventData.päivä + '</p>' +
                                        '<center><h2><b>' + eventData.otsikko + '</b></h2>' +
                                        '<p>' + eventData.tietoa + '<br></p>' +
                                        '</center></div>';
        }
    }
};
xhr.send();




});

function clearImages() {
    imageForm = document.getElementById('clearImageForm');
    imageForm.submit();
}

function clearBullets() {
    bulletForm = document.getElementById('clearBulletForm');
    bulletForm.submit();
}


function submitForm(selectedForm, selectedDisplay) {
    console.log(selectedForm, selectedDisplay);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(selectedDisplay).innerHTML = this.responseText;
        }
    };
    xhttp.open("POST", "kirjaudu/server/eventHandler.php", true);
    var formData = new FormData(document.getElementById(selectedForm));
    xhttp.send(formData);
}
