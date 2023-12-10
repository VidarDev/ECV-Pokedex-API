function toogleMenu(element)
{
    if (element.classList.contains("show")) {
        element.classList.remove("show");
    } else {
        element.classList.add("show");
    }
}

function UICards(data) {
    const container = document.querySelector("#nav .card-container");
    const nextButton = document.getElementById('next-page');
    container.innerHTML = "";

    if (data.length < 25) {
        nextButton.classList.add('disabled');
    } else {
        nextButton.classList.remove('disabled');
    }

    for (let i = 0; i < data.length - 1; i++) {
        container.innerHTML += data[i];
    }
}

function displayMenuList(data)
{
    UICards(data);
}

function AJAXMenuList(generation, type, page)
{
    // Data to send
    let data = {
        generation: generation,
        type: type,
        page: page
    };

    // Sending AJAX request with fetch
    fetch('../ajax/list.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            displayMenuList(data);
        })
        .catch(error => {
            document.querySelector("#nav .card-container").innerHTML = "";
        });
}

document.addEventListener('DOMContentLoaded', () => {
    const radomButton = document.getElementById('random');
    radomButton.addEventListener('click', () => {
        location.search = 'id=random';
    });

    const menuButton = document.getElementById('nav-menu');
    const navElement = document.getElementById('nav');
    menuButton.addEventListener('click', () => {
        toogleMenu(navElement);
    });

    let inputValueGen = document.filter.generation;
    let inputValueType = document.filter.types;
    let inputValueTypePrev = null;

    let nextButton = document.getElementById('next-page');
    let prevButton = document.getElementById('prev-page');
    let actualPage = document.getElementById('actual-page');

    for (let i = 0; i < inputValueType.length; i++) {
        inputValueType[i].addEventListener('change', function() {
            inputValueTypePrev ? inputValueTypePrev : null;
            if (this !== inputValueTypePrev) inputValueTypePrev = this;

            nextButton.value = 2;
            prevButton.value = 0;
            AJAXMenuList(inputValueGen.value, this.value, actualPage.value);
        });
    }
    inputValueGen.addEventListener('change', () => {
        nextButton.value = 2;
        prevButton.value = 0;
        AJAXMenuList(inputValueGen.value, inputValueType.value, actualPage.value);
    });
    nextButton.addEventListener('click', () => {
        AJAXMenuList(inputValueGen.value, inputValueType.value, nextButton.value);
        nextButton.value++;
        prevButton.value++;
    });
    prevButton.addEventListener('click', () => {
        AJAXMenuList(inputValueGen.value, inputValueType.value, prevButton.value);
        prevButton.value--;
        nextButton.value--;
    });

    AJAXMenuList(inputValueGen.value, inputValueType.value, actualPage.value);
});