<nav class="nav" id="nav">

    <div class="nav__button" id="nav-menu">
        <img src="./img/pokeball.svg" role='presentation' alt='Pokeball' title='Pokeball' aria-label='Pokeball' loading='lazy' width='200' height='200'/>
    </div>
    <div class="nav__content">
        <form action="index.php" method="post" class="generation">
            <label for="generationSelect"></label>
            <select id="generationSelect" class="select" name="generationSelect">
                <option value="1">Gen 1</option>
                <option value="2">Gen 2</option>
                <option value="3">Gen 3</option>
                <option value="4">Gen 4</option>
                <option value="5">Gen 5</option>
                <option value="6">Gen 6</option>
                <option value="7">Gen 7</option>
                <option value="8">Gen 8</option>
            </select>
            <input type="submit" class="btn" value="Chercher">
        </form>
        <div class="card-container">
            <?php
                $selectedGeneration = 1;
                $pokemonList = $dao->getPokemonIdByGeneration($selectedGeneration);

                foreach ($pokemonList as $pokemon) {
                    include __DIR__ . '/../card.php';
                }
            ?>
        </div>
    </div>
</nav>