<?php
$dao = new DAO();
?>
<nav class="nav" id="nav">

    <div class="nav__button" id="nav-menu">
        <img src="./img/pokeball.svg" role='presentation' alt='Pokeball' title='Pokeball' aria-label='Pokeball' loading='lazy' width='200' height='200'/>
    </div>
    <div class="nav__content">
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
        <div class="card-container">
            <?php
                $selectedGeneration = 1;
                $pokemonList = $dao->getPokemonByGeneration($selectedGeneration);

                foreach ($pokemonList as $pokemon) {
                    // Utilisez la fonction formatPokemons pour formater la carte du Pokémon
                    echo $dao->UIPokemonCard($pokemon);
                }
            ?>
        </div>
    </div>
</nav>