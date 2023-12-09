<?php
include __DIR__ . '/components/layouts/header.php';
include __DIR__ . '/components/layouts/nav.php';

$formatPokedexId = formatPokedexId($pokemon['id']);
?>

<?php if(): ?>

<div class='pokemon <?= {$pokemon['pokemon_type_first_name_EN']} ?>'>
    <span class='pokemon__pokedex-id'>#{$formatPokedexId}</span>
    <div class='pokemon-top'>
        <div class='pokemon-top__identity'>
            <div class='pokemon-types'>
                <span>
                    <img src='<?= {$pokemon['pokemon_type_first_image']} ?>' role='img' alt='<?= {$pokemon['pokemon_type_first_name_FR']} ?>' title='<?= {$pokemon['pokemon_type_first_name_FR']} ?>' aria-label='<?= {$pokemon['pokemon_type_first_name_FR']} ?>' loading='lazy' width='200' height='200'/>
                    <?= {$pokemon['pokemon_type_first_name_FR']} ?>
                </span>
                <?php var_dump({$pokemon['pokemonTypesSecondId']}); ?>
                <?php if ({$pokemon['pokemonTypesSecondId']} !== null): ?>
                    <span>
                        <img src='<?= {$pokemon['pokemon_type_second_image']} ?>' role='img' alt='<?= {$pokemon['pokemon_type_second_name_FR']} ?>' title='<?= {$pokemon['pokemon_type_second_name_FR']} ?>' aria-label='<?= {$pokemon['pokemon_type_second_name_FR']} ?>' loading='lazy' width='200' height='200'/>
                        <?= {$pokemon['pokemon_type_second_name_FR']} ?>
                    </span>
                <?php endif ?>
            </div>
            <h1 class='pokemon-name'><?= {$pokemon['name']} ?></h1>
        </div>
        <div class='pokemon-top__images'>
            <div class='pokemon-current'>
                <img src='<?= {$pokemon['image']} ?>' role='img' alt='<?= {$pokemon['name']} ?>' title='<?= {$pokemon['name']} ?>' aria-label='<?= {$pokemon['name']} ?>' loading='lazy' width='200' height='200'/>
            </div>
            <div class='pokemon-evolutions'>
                <h2 class='screen-reader-only'>Evolutions</h2>
                <div class='pokemon-evolutions__next'>
                    <img src='<?= {$pokemon['image']} ?>' role='img' alt='<?= {} ?>' title='<?= {} ?>' aria-label='<?= {} ?>' loading='lazy' width='200' height='200'/>
                    <h3 class='pokemon-name'><?= {} ?></h3>
                </div>
                <div class='pokemon-evolutions__prev'>
                    <img src='<?= {$pokemon['image']} ?>' role='img' alt='<?= {} ?>' title='<?= {} ?>' aria-label='<?= {} ?>' loading='lazy' width='200' height='200'/>
                    <h3 class='pokemon-name'><?= {} ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class='pokemon-bottom'>
        <h2 class='pokemon-bottom__title'>Stats</h2>
        <div class='pokemon-bottom__wrapper'>
            <div class='stats-items'>
                <h3 class='stats-items__label'>HP</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= {$pokemon['hp']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= {$pokemon['hp']} ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Attack</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= {$pokemon['attack']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= {$pokemon['attack']} ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Defense</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc(({<?= $pokemon['defense']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= {$pokemon['defense']} ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Special Attack</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= {$pokemon['special_attack']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= {$pokemon['special_attack']} ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Special Defense</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= {$pokemon['special_defense']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><? {$pokemon['special_defense']} ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Speed</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc(({<?= $pokemon['speed']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= {$pokemon['speed']} ?></span>
            </div>
        </div>
    </div>
</div>

<?php endif ?>

<?php
include __DIR__ . '/layout/footer.php';
?>
