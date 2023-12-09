<?php
include __DIR__ . '/components/layouts/header.php';
include __DIR__ . '/components/layouts/nav.php';

$formatPokedexId = formatPokedexId($pokemon['pokemonId']);
?>

<?php if(): ?>

<div class='pokemon <?= {$pokemon['pokemonTypesFirstName_EN']} ?>'>
    <span class='pokemon__pokedex-id'>#{$formatPokedexId}</span>
    <div class='pokemon-top'>
        <div class='pokemon-top__identity'>
            <div class='pokemon-types'>
                <span>
                    <img src='<?= {$pokemon['pokemonTypesFirstImage']} ?>' role='img' alt='<?= {$pokemon['pokemonTypesFirstName']} ?>' title='<?= {$pokemon['pokemonTypesFirstName']} ?>' aria-label='<?= {$pokemon['pokemonTypesFirstName']} ?>' loading='lazy' width='200' height='200'/>
                    <?= {$pokemon['pokemonTypesFirstName']} ?>
                </span>
                <?php var_dump({$pokemon['pokemonTypesSecondId']}); ?>
                <?php if ({$pokemon['pokemonTypesSecondId']} !== null): ?>
                    <span>
                        <img src='<?= {$pokemon['pokemonTypesSecondImage']} ?>' role='img' alt='<?= {$pokemon['pokemonTypesSecondName']} ?>' title='<?= {$pokemon['pokemonTypesSecondName']} ?>' aria-label='<?= {$pokemon['pokemonTypesSecondName']} ?>' loading='lazy' width='200' height='200'/>
                        <?= {$pokemon['pokemonTypesSecondName']} ?>
                    </span>
                <?php endif ?>
            </div>
            <h1 class='pokemon-name'><?= {$pokemon['pokemonName']} ?></h1>
        </div>
        <div class='pokemon-top__images'>
            <div class='pokemon-current'>
                <img src='<?= {$pokemon['pokemonImage']} ?>' role='img' alt='<?= {$pokemon['pokemonName']} ?>' title='<?= {$pokemon['pokemonName']} ?>' aria-label='<?= {$pokemon['pokemonName']} ?>' loading='lazy' width='200' height='200'/>
            </div>
            <div class='pokemon-evolutions'>
                <h2 class='screen-reader-only'>Evolutions</h2>
                <div class='pokemon-evolutions__next'>
                    <img src='<?= {$pokemon['pokemonImage']} ?>' role='img' alt='<?= {$pokemon['pokemonNextEvolName']} ?>' title='<?= {$pokemon['pokemonNextEvolName']} ?>' aria-label='<?= {$pokemon['pokemonNextEvolName']} ?>' loading='lazy' width='200' height='200'/>
                    <h3 class='pokemon-name'><?= {$pokemon['pokemonNextEvolName']} ?></h3>
                </div>
                <div class='pokemon-evolutions__prev'>
                    <img src='<?= {$pokemon['pokemonImage']} ?>' role='img' alt='<?= {$pokemon['pokemonPrevEvolName']} ?>' title='<?= {$pokemon['pokemonPrevEvolName']} ?>' aria-label='<?= {$pokemon['pokemonPrevEvolName']} ?>' loading='lazy' width='200' height='200'/>
                    <h3 class='pokemon-name'><?= {$pokemon['pokemonPrevEvolName']} ?></h3>
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
                    <span class='indicator' style='width: calc((<?= {$pokemon['pokemonStatsHp']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= {$pokemon['pokemonStatsHp']} ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Attack</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= {$pokemon['pokemonStatsAttack']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= {$pokemon['pokemonStatsAttack']} ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Defense</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc(({<?= $pokemon['pokemonStatsDefense']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= {$pokemon['pokemonStatsDefense']} ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Special Attack</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= {$pokemon['pokemonStatsSpecialAttack']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= {$pokemon['pokemonStatsSpecialAttack']} ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Special Defense</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc((<?= {$pokemon['pokemonStatsSpecialDefense']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><? {$pokemon['pokemonStatsSpecialDefense']} ?></span>
            </div>
            <div class='stats-items'>
                <h3 class='stats-items__label'>Speed</h3>
                <div class='stats-items__progress'>
                    <span class='indicator' style='width: calc(({<?= $pokemon['pokemonStatsSpeed']} ?> * 100%) / 255);'></span>
                </div>
                <span class='stats-items__value'><?= {$pokemon['pokemonStatsSpeed']} ?></span>
            </div>
        </div>
    </div>
</div>

<?php endif ?>

<?php
include __DIR__ . '/layout/footer.php';
?>
