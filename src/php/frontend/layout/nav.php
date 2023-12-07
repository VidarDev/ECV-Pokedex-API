<nav class="nav" id="nav">
    <form action="index.php" method="post" class="pokemon">
        <label for="pokemonInput"></label>
        <input type="text" class="btn" id="pokemonInput" name="pokemonInput" placeholder="Entrez un ID ou un nom">
        <input type="submit" class="btn" value="Chercher">
    </form>

    <form action="index.php" method="post" class="generation">
        <label for="generationSelect"></label>
        <select id="generationSelect" class="select" name="generationSelect">
            <option value="1">Génération 1</option>
            <option value="2">Génération 2</option>
            <option value="3">Génération 3</option>
            <option value="4">Génération 4</option>
            <option value="5">Génération 5</option>
            <option value="6">Génération 6</option>
            <option value="7">Génération 7</option>
            <option value="8">Génération 8</option>
        </select>
        <input type="submit" class="btn" value="Chercher">
    </form>
</nav>