<?php
include __DIR__ . '/../backend/database.php';
include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/nav.php';

$dao = new DAO();

//var_dump($dao->api->getPokemonByPokedexID(888));
//var_dump($dao->getPokemon(888));
var_dump($dao->getTypeByName("Eau"));
?>

<?php
include __DIR__ . '/layout/footer.php';
?>
