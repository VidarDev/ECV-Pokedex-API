<?php
function formatString($str) {
    // Supprime tous les espaces
    $strNoSpaces = str_replace(' ', '', $str);

    // Convertit la chaîne en minuscules
    $strLowercase = strtolower($strNoSpaces);

    // Convertit le premier caractère en majuscule
    $strFiltered = ucfirst($strLowercase);

    return $strFiltered;
}

function formatPokedexId($number) {
    return sprintf("%03d", $number);
}

function downloadPokemonImage($imageUrl, $pokedexId, $name) {
    $imagePath = "./img/pokemons/{$pokedexId}_{$name}.png";

    // Utilisez file_get_contents et file_put_contents pour télécharger et sauvegarder l'image
    $imageData = file_get_contents($imageUrl);
    if ($imageData !== false && !file_exists($imagePath)) {
        file_put_contents($imagePath, $imageData);
    }

    return $imageData !== false ? $imagePath : null;
}

function downloadTypeImage($imageUrl, $name) {
    $imagePath = "./img/types/{$name}.png";

    // Utilisez file_get_contents et file_put_contents pour télécharger et sauvegarder l'image
    $imageData = file_get_contents($imageUrl);
    if ($imageData !== false && !file_exists($imagePath)) {
        file_put_contents($imagePath, $imageData);
    }

    return $imageData !== false ? $imagePath : null;
}

?>