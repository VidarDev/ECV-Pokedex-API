<?php
    $generations = $dao->getGenerationsAll();
    $types = $dao->getTypesAll();
?>
<nav class="nav" id="nav">

    <div class="nav__button" id="nav-menu">
        <img src="./img/pokeball.svg" role='presentation' alt='Pokeball' title='Pokeball' aria-label='Pokeball' loading='lazy' width='200' height='200'/>
    </div>
    <div class="nav__content">
        <form action="index.php" method="post" class="generation" name="filter">
            <label for="generation" class="screen-reader-only"></label>
            <select id="generation" class="select" name="generation">
                <option value="0" selected>All</option>
                <?php foreach($generations as $generation): ?>
                    <option value="<?= $generation['generation']?>">Gen <?= $generation['generation']?></option>
                <?php endforeach; ?>
            </select>
            <fieldset>
                <label for="all-types">All
                    <input type="radio" id="all-types" name="types" value="0" checked>
                </label>
                <?php foreach($types as $type): ?>
                    <label for="<?= $type['id_type'] ?>">
                        <input type="radio" id="<?= $type['id_type'] ?>" name="types" value="<?= $type['id_type'] ?>">
                        <img src='<?= $type['image'] ?>' role='img' alt='<?= $type['name_FR'] ?>' title='<?= $type['name_FR'] ?>' aria-label='<?= $type['name_FR'] ?>' loading='lazy' width='16' height='16'/>
                    </label>
                <?php endforeach; ?>
            </fieldset>
        </form>
        <div class="card-container">
            <?php
                $pokemonList = $dao->getPokemonsListAllTypesAndGeneration(1);

                foreach ($pokemonList as $pokemon) {
                    include __DIR__ . '/../card.php';
                }
            ?>
        </div>
    </div>
</nav>