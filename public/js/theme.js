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

    if (data.length < 26) {
        document.getElementById('next-page').classList.add('disabled');
    } else {
        document.getElementById('next-page').classList.remove('disabled');
    }

    for (let i = 0; i < data.length - 1; i++) {
        container.innerHTML += data[i];
    }
}

function listPokemon(generation, type, page) {
    // Données à envoyer
    let data = {
        generation: generation,
        type: type,
        page: page
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

    var container = document.querySelector("#nav .card-container");
    var inputValueGen = document.filter.generation;
    var inputValueType = document.filter.types;
    var inputValueTypePrev = null;
    var nextButton = document.getElementById('next-page');
    var prevButton = document.getElementById('prev-page');
    var actualPage = nextButton.value - 1;

    for (var i = 0; i < inputValueType.length; i++) {
        inputValueType[i].addEventListener('change', function() {
            inputValueTypePrev ? inputValueTypePrev : null;
            if (this !== inputValueTypePrev) {
                inputValueTypePrev = this;
            }
            nextButton.value = 2;
            prevButton.value = 0;
            listPokemon(inputValueGen.value, this.value, actualPage)
        });
    }
    inputValueGen.addEventListener('change', () => {
        nextButton.value = 2;
        prevButton.value = 0;
        listPokemon(inputValueGen.value, inputValueType.value, actualPage)
    });

    nextButton.addEventListener('click', () => {
        listPokemon(inputValueGen.value, inputValueType.value, nextButton.value);
        nextButton.value++;
        prevButton.value++;
    });
    prevButton.addEventListener('click', () => {
        listPokemon(inputValueGen.value, inputValueType.value, prevButton.value);
        prevButton.value--;
        nextButton.value--;
    })
});