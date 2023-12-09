<?php
include __DIR__ . '/../backend/database.php';

$dao = new DAO();

if (isset($_POST['pokemonInput'])) {
    $params = $_POST['pokemonInput'];
    header("Location: pokemon.php?id=" . $params);
} else {
    header("Location: pokemon.php?id=random");
}
?>