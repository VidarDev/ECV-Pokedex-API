<?php
include __DIR__ . '/components/layouts/header.php';
include __DIR__ . '/components/layouts/nav.php';

$dao = new DAO();

if (isset($_POST['pokemonInput'])) {
    $params = $_POST['pokemonInput'];
} else {
    $params = $dao->getRandomPokemonID();
}

?>
<?php include __DIR__ . '/pokemon.php'; ?>

<?php
include __DIR__ . '/components/layouts/footer.php';
?>
