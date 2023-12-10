<?php
    include __DIR__ . '/../../backend/database.php';
    $dao = new Dao();

    $dao->checkIfTypesExists();

    if ($dao->checkIfPokemonsExists() === false) {
        $dao->addPokemonsInit(24);
    }
?>

<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <title>Pokédex API | VidarDev</title>
    <meta name="description" content="Recherchez un Pokémon par son nom ou son numéro de Pokédex national." />
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <link rel="shortlink" href="/" />

    <link rel="icon" href="./favicon.svg" sizes="any" type="image/x-icon" />
    <link rel="apple-touch-icon" href="./favicon.png" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link as="style" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet preload" async />

    <link rel="stylesheet" type="text/css" href="./css/style.css" media="all"/>

    <script type="text/javascript" src="./js/theme.js"></script>
</head>
<body>
<header id="header" class="header">
    <form action="index.php" method="post" class="search-pokemon">
        <label for="pokemonInput" class="screen-reader-only"></label>
        <input type="text" class="btn" id="pokemonInput" name="pokemonInput" placeholder="ID ou Nom" required>
        <button type="button" class="btn random" id="random">
            <img src='./img/icons/shuffle.svg' role='img' alt='Chercher un Pokémon aléatoire' title='Chercher un Pokémon aléatoire' aria-label='Chercher un Pokémon aléatoire' loading='lazy' width='16' height='16'/>
        </button>
        <button type="submit" class="btn search">
            <img src='./img/icons/search.svg' role='img' alt='Chercher le Pokémon saisis' title='Chercher le Pokémon saisis' aria-label='Chercher le Pokémon saisis' loading='lazy' width='16' height='16'/>
        </button>
    </form>
</header>
<main id="main">