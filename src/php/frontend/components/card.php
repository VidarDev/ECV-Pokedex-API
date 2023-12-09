<?php
?>

<?php if(): ?>

<div class='pokemon-card <?= {$pokemon['pokemon_type_first_name_EN']} ?>'>
    <div class='pokemon-card-top'>
        <span class='pokemon-card-top__name'><?= {$pokemon['name']} ?></span>
        <span class='pokemon-card-top__pokedex'>#<?= {$formatPokedexId} ?></span>
    </div>
    <div class='pokemon-card-bottom'>
        <div class='pokemon-card-bottom__types'>
            <img src='<?= {$pokemon['pokemon_type_first_image']} ?>' role='img' alt='<?= {$pokemon['pokemon_type_first_name_FR']} ?>' title='<?= {$pokemon['pokemon_type_first_name_FR']} ?>' aria-label='<?= {$pokemon['pokemon_type_first_name_FR']} ?>' loading='lazy' width='200' height='200'/>
            <?php var_dump({$pokemon['pokemonTypesSecondId']}); ?>
            <?php if ({$pokemon['pokemonTypesSecondId']} !== null): ?>
                <img src='<?= {$pokemon['pokemon_type_second_image']} ?>' role='img' alt='<?= {$pokemon['pokemon_type_second_name_FR']} ?>' title='<?= {$pokemon['pokemon_type_second_name_FR']} ?>' aria-label='<?= $pokemon['pokemon_type_second_name_FR']} ?>' loading='lazy' width='200' height='200'/>
            <?php endif ?>
        </div>
        <img class='pokemon-card-bottom__image' src='<?= {$pokemon['image']} ?>' role='img' alt='<?= {$pokemon['name']} ?>' title='<?= {$pokemon['name']} ?>' aria-label='<?= {$pokemon['name']} ?>' loading='lazy' width='200' height='200'/>
    </div>
</div

<?php endif ?>
