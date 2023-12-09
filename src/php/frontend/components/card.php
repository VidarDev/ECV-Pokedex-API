<?php
?>

<?php if(): ?>

<div class='pokemon-card <?= {$pokemon['pokemonTypesFirstName_EN']} ?>'>
    <div class='pokemon-card-top'>
        <span class='pokemon-card-top__name'><?= {$pokemon['pokemonName']} ?></span>
        <span class='pokemon-card-top__pokedex'>#<?= {$formatPokedexId} ?></span>
    </div>
    <div class='pokemon-card-bottom'>
        <div class='pokemon-card-bottom__types'>
            <img src='<?= {$pokemon['pokemonTypesFirstImage']} ?>' role='img' alt='<?= {$pokemon['pokemonTypesFirstName']} ?>' title='<?= {$pokemon['pokemonTypesFirstName']} ?>' aria-label='<?= {$pokemon['pokemonTypesFirstName']} ?>' loading='lazy' width='200' height='200'/>
            <?php var_dump({$pokemon['pokemonTypesSecondId']}); ?>
            <?php if ({$pokemon['pokemonTypesSecondId']} !== null): ?>
                <img src='<?= {$pokemon['pokemonTypesSecondImage']} ?>' role='img' alt='<?= {$pokemon['pokemonTypesSecondName']} ?>' title='<?= {$pokemon['pokemonTypesSecondName']} ?>' aria-label='<?= $pokemon['pokemonTypesSecondName']} ?>' loading='lazy' width='200' height='200'/>
            <?php endif ?>
        </div>
        <img class='pokemon-card-bottom__image' src='<?= {$pokemon['pokemonImage']} ?>' role='img' alt='<?= {$pokemon['pokemonName']} ?>' title='<?= {$pokemon['pokemonName']} ?>' aria-label='<?= {$pokemon['pokemonName']} ?>' loading='lazy' width='200' height='200'/>
    </div>
</div

<?php endif ?>
