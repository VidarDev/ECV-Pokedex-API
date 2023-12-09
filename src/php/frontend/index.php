<?php
include __DIR__ . '/components/layouts/header.php';
include __DIR__ . '/components/layouts/nav.php';

$dao = new DAO();

$params = 0;

//if (isset($_POST['pokemonInput'])) {
//    $input = $_POST['pokemonInput'];
//
//    $pokemon = $dao->getPokemonByIdOrName($input);
//
//    echo $dao->UIPokemon($pokemon);
//
//} elseif (isset($_POST['generationSelect'])) {
//    $selectedGeneration = $_POST['generationSelect'];
//    $pokemonList = $dao->getPokemonByGeneration($selectedGeneration);
//
//    echo '<div class="pokemon-list__wrapper">';
//    foreach ($pokemonList as $pokemon) {
//        // Utilisez la fonction formatPokemons pour formater la carte du Pokémon
//        echo $dao->UIPokemonCard($pokemon);
//    }
//    echo '</div>';
//}
?>
<?php include __DIR__ . '/pokemon.php'; ?>

<?php
include __DIR__ . '/components/layouts/footer.php';
?>
