function toogleMenu(element) {
    if (element.classList.contains("show")) {
        element.classList.remove("show");
    } else {
        element.classList.add("show");
    }
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
});