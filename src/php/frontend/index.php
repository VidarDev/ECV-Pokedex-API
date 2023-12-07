<?php
include __DIR__ . '/../backend/database.php';
include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/nav.php';

$dao = new DAO();

if (isset($_POST['pokemonInput'])) {
    $input = $_POST['pokemonInput'];

    $pokemon = $dao->getPokemon($input);

    echo $dao->UIPokemon($pokemon[0]);

} elseif (isset($_POST['generationSelect'])) {
    $selectedGeneration = $_POST['generationSelect'];
    $pokemonList = $dao->getPokemonByGeneration($selectedGeneration);

    echo '<div class="pokemon-list__wrapper">';
    foreach ($pokemonList as $pokemon) {
        // Utilisez la fonction formatPokemons pour formater la carte du Pokémon
        echo $dao->UIPokemonCard($pokemon);
    }
    echo '</div>';
}

?>

<?php
include __DIR__ . '/layout/footer.php';
?>
