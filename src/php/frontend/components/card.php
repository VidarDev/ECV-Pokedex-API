<?php
$pokemonCard = $dao->getPokemonCard($pokemon['id']);

$formatPokedexId = formatPokedexId($pokemonCard['id']);
?>

<a class='pokemon-card <?= $pokemonCard['pokemon_type_first_name_EN'] ?>' href="?id=<?= $pokemonCard['id'] ?>">
    <div class='pokemon-card-top'>
        <span class='pokemon-card-top__name'><?= $pokemonCard['name'] ?></span>
        <span class='pokemon-card-top__pokedex'>#<?= $formatPokedexId ?></span>
    </div>
    <div class='pokemon-card-bottom'>
        <div class='pokemon-card-bottom__types'>
            <img src='<?= $pokemonCard['pokemon_type_first_image'] ?>' role='img' alt='<?= $pokemonCard['pokemon_type_first_name_FR'] ?>' title='<?= $pokemonCard['pokemon_type_first_name_FR'] ?>' aria-label='<?= $pokemonCard['pokemon_type_first_name_FR'] ?>' loading='lazy' width='200' height='200'/>
            <?php if (isset($pokemonCard['id_type_second'])): ?>
                <img src='<?= $pokemonCard['pokemon_type_second_image'] ?>' role='img' alt='<?= $pokemonCard['pokemon_type_second_name_FR'] ?>' title='<?= $pokemonCard['pokemon_type_second_name_FR'] ?>' aria-label='<?= $pokemonCard['pokemon_type_second_name_FR'] ?>' loading='lazy' width='200' height='200'/>
            <?php endif; ?>
        </div>
        <img class='pokemon-card-bottom__image' src='<?= $pokemonCard['image'] ?>' role='img' alt='<?= $pokemonCard['name'] ?>' title='<?= $pokemonCard['name'] ?>' aria-label='<?= $pokemonCard['name'] ?>' loading='lazy' width='200' height='200'/>
    </div>
</a>
