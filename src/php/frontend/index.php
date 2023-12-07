<?php
include __DIR__ . '/../backend/database.php';
include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/nav.php';

$dao = new DAO();

$dao->getPokemon(888);

if (isset($_POST['pokemonInput'])) {
    $input = $_POST['pokemonInput'];

    $pokemon = $dao->getPokemon($input);

    var_dump($input);
    var_dump($pokemon);

    echo $dao->UIPokemonCard($pokemon);
}

?>

<?php
include __DIR__ . '/layout/footer.php';
?>
