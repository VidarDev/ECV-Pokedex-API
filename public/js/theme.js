function toogleMenu(element) {
    if (element.classList.contains("show")) {
        element.classList.remove("show");
    } else {
        element.classList.add("show");
    }
}

function displayCard(data) {
    const container = document.querySelector("#nav .card-container");
    container.innerHTML = "";

    data.forEach((card) => {
        container.innerHTML += card;
    })
}

function listPokemon(generation, type) {
    // Données à envoyer
    let data = {
        generation: generation,
        type: type
    };

    console.log(data);

    // Envoi de la requête AJAX avec fetch
    fetch('../ajax/list.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            displayCard(data);
        })
        .catch(error => console.error('Erreur:', error));
}

document.addEventListener('DOMContentLoaded', () => {
    const menuButton = document.getElementById('nav-menu');
    const navElement = document.getElementById('nav');

    menuButton.addEventListener('click', () => {
        toogleMenu(navElement);
    });

    document.getElementById('random').addEventListener('click', () => {
        location.search = 'id=random';
    });

    var inputValueGen = document.filter.generation;
    var inputValueType = document.filter.types;
    var inputValueTypePrev = null;
    for (var i = 0; i < inputValueType.length; i++) {
        inputValueType[i].addEventListener('change', function() {
            inputValueTypePrev ? inputValueTypePrev : null;
            if (this !== inputValueTypePrev) {
                inputValueTypePrev = this;
            }
            listPokemon(inputValueGen.value, this.value)
        });
    }

    inputValueGen.addEventListener('change', () => listPokemon(inputValueGen.value, inputValueType.value));
});