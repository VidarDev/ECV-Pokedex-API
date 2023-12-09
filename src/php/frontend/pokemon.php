<?php
include __DIR__ . '/components/layouts/header.php';
include __DIR__ . '/components/layouts/nav.php';

$dao = new DAO();

if (isset($_GET["id"]) && $_GET["id"] != "random")
    $params = $_GET["id"];
else {
    $params = $dao->getRandomPokemonID();
}

$pokemon = $dao->getPokemonByIdOrName($params);
if($pokemon === false) {
    $pokemon = [
        'id' => '0',
        'name' => 'Unknown',
        'image' => './img/unknown.png',
        'pokemon_type_first_name_EN' => 'unknown',
        'hp' => '0',
        'attack' => '0',
        'defense' => '0',
        'special_attack' => '0',
        'special_defense' => '0',
        'speed' => '0',
    ];
}
if (isset($pokemon['id_next_evolution'])) {
    $next_evolution = $dao->getEvolutionById($pokemon['id_next_evolution']);
    $pokemon['name_next_evolution'] = $next_evolution['name'];
    $pokemon['image_next_evolution'] = $next_evolution['image'];
}
if (isset($pokemon['id_prev_evolution'])) {
    $prev_evolution = $dao->getEvolutionById($pokemon['id_prev_evolution']);
    $pokemon['name_prev_evolution'] = $prev_evolution['name'];
    $pokemon['image_prev_evolution'] = $prev_evolution['image'];
}

$formatPokedexId = formatPokedexId($pokemon['id']);
?>

<div class='pokemon <?= $pokemon['pokemon_type_first_name_EN'] ?>'>
    <span class='pokemon__pokedex-id'>#<?= $formatPokedexId ?></span>
    <div class='pokemon-top'>
        <div class='pokemon-top__identity'>
            <div class='pokemon-types'>
                <?php if (isset($pokemon['id_type_first'])): ?>
                    <span>
                        <img src='<?= $pokemon['pokemon_type_first_image'] ?>' role='img' alt='<?= $pokemon['pokemon_type_first_name_FR'] ?>' title='<?= $pokemon['pokemon_type_first_name_FR'] ?>' aria-label='<?= $pokemon['pokemon_type_first_name_FR'] ?>' loading='lazy' width='200' height='200'/>
                        <?= $pokemon['pokemon_type_first_name_FR'] ?>
                    </span>
                <?php endif; ?>
                <?php if (isset($pokemon['id_type_second'])): ?>
                    <span>
                        <img src='<?= $pokemon['pokemon_type_second_image'] ?>' role='img' alt='<?= $pokemon['pokemon_type_second_name_FR'] ?>' title='<?= $pokemon['pokemon_type_second_name_FR'] ?>' aria-label='<?= $pokemon['pokemon_type_second_name_FR'] ?>' loading='lazy' width='200' height='200'/>
                        <?= $pokemon['pokemon_type_second_name_FR'] ?>
                    </span>
                <?php endif; ?>
            </div>
            <h1 class='pokemon-name'><?= $pokemon['name'] ?></h1>
        </div>
        <div class='pokemon-top__images'>
            <div class='pokemon-current'>
                <img src='<?= $pokemon['image'] ?>' role='img' alt='<?= $pokemon['name'] ?>' title='<?= $pokemon['name'] ?>' aria-label='<?= $pokemon['name'] ?>' loading='lazy' width='200' height='200'/>
            </div>
            <div class='pokemon-evolutions'>
                <h2 class='screen-reader-only'>Evolutions</h2>
                <?php if (isset($pokemon['id_prev_evolution'])): ?>
                <a class='pokemon-evolutions__prev' href="?id=<?= $pokemon['id_prev_evolution'] ?>">
                    <img src='<?= $pokemon['image_prev_evolution'] ?>' role='img' alt='<?= $pokemon['name_prev_evolution'] ?>' title='<?= $pokemon['name_prev_evolution'] ?>' aria-label='<?= $pokemon['name_prev_evolution'] ?>' loading='lazy' width='200' height='200'/>
                    <h3 class='pokemon-name'><?= $pokemon['name_prev_evolution'] ?></h3>
                </a>
                <?php endif; ?>
                <?php if (isset($pokemon['id_next_evolution'])): ?>
                    <a class='pokemon-evolutions__next' href="?id=<?= $pokemon['id_next_evolution'] ?>">
                        <img src='<?= $pokemon['image_next_evolution'] ?>' role='img' alt='<?= $pokemon['name_next_evolution'] ?>' title='<?= $pokemon['name_next_evolution'] ?>' aria-label='<?= $pokemon['name_next_evolution'] ?>' loading='lazy' width='200' height='200'/>
                        <h3 class='pokemon-name'><?= $pokemon['name_next_evolution'] ?></h3>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class='pokemon-bottom'>
        <h2 class='pokemon-bottom__title'>Stats</h2>
        <div class='pokemon-bottom__wrapper'>
            <div class='stats-items'>
                <h3 class='stats-items__label'>PV</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= $pokemon['hp'] ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= $pokemon['hp'] ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Attaque</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= $pokemon['attack'] ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= $pokemon['attack'] ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Défense</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= $pokemon['defense'] ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= $pokemon['defense'] ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Attaque Spéciale</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= $pokemon['special_attack'] ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= $pokemon['special_attack'] ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Défense Spéciale</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= $pokemon['special_defense'] ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= $pokemon['special_defense'] ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Vitesse</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= $pokemon['speed'] ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= $pokemon['speed'] ?></span>
            </div>
        </div>
    </div>
</div>
