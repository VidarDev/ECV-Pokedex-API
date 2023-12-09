<?php
    $generations = $dao->getGenerations();
?>
<nav class="nav" id="nav">

    <div class="nav__button" id="nav-menu">
        <img src="./img/pokeball.svg" role='presentation' alt='Pokeball' title='Pokeball' aria-label='Pokeball' loading='lazy' width='200' height='200'/>
    </div>
    <div class="nav__content">
        <form action="index.php" method="post" class="generation">
            <label for="generationSelect"></label>
            <select id="generationSelect" class="select" name="generationSelect">
                <option value="0" selected>All</option>
                <?php foreach($generations as $generation): ?>
                    <option value="<?= $generation[0]?>">Gen <?= $generation[0]?></option>
                <?php endforeach; ?>
            </select>
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