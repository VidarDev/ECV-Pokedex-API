<?php
$pokemon = $dao->getPokemonById($params);
$todo = "TODO";

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
                <?php endif ?>
                <?php if (isset($pokemon['id_type_second'])): ?>
                    <span>
                        <img src='<?= $pokemon['pokemon_type_second_image'] ?>' role='img' alt='<?= $pokemon['pokemon_type_second_name_FR'] ?>' title='<?= $pokemon['pokemon_type_second_name_FR'] ?>' aria-label='<?= $pokemon['pokemon_type_second_name_FR'] ?>' loading='lazy' width='200' height='200'/>
                        <?= $pokemon['pokemon_type_second_name_FR'] ?>
                    </span>
                <?php endif ?>
            </div>
            <h1 class='pokemon-name'><?= $pokemon['name'] ?></h1>
        </div>
        <div class='pokemon-top__images'>
            <div class='pokemon-current'>
                <img src='<?= $pokemon['image'] ?>' role='img' alt='<?= $pokemon['name'] ?>' title='<?= $pokemon['name'] ?>' aria-label='<?= $pokemon['name'] ?>' loading='lazy' width='200' height='200'/>
            </div>
            <div class='pokemon-evolutions'>
                <h2 class='screen-reader-only'>Evolutions</h2>
                <?php if (isset($pokemon['id_next_evolution'])): ?>
                <a class='pokemon-evolutions__next' id="<?= $pokemon['id_next_evolution'] ?>">
                    <img src='<?= $pokemon['image'] ?>' role='img' alt='<?= $todo ?>' title='<?= $todo ?>' aria-label='<?= $todo ?>' loading='lazy' width='200' height='200'/>
                    <h3 class='pokemon-name'><?= $todo ?></h3>
                </a>
                <?php endif ?>
                <?php if (isset($pokemon['id_prev_evolution'])): ?>
                <a class='pokemon-evolutions__prev' id="<?= $pokemon['id_prev_evolution'] ?>">
                    <img src='<?= $pokemon['image'] ?>' role='img' alt='<?= $todo ?>' title='<?= $todo ?>' aria-label='<?= $todo ?>' loading='lazy' width='200' height='200'/>
                    <h3 class='pokemon-name'><?= $todo ?></h3>
                </a>
                <?php endif ?>
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
