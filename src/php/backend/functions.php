<?php
function formatString($str): string
{
    // Remove all spaces
    $strNoSpaces = str_replace(' ', '', $str);

    // Convert string to lowercase
    $strLowercase = strtolower($strNoSpaces);

    // Converts first character to uppercase
    return ucfirst($strLowercase);
}

function formatPokedexId($number): string
{
    return sprintf("%03d", $number);
}

function downloadPokemonImage($imageUrl, $pokedexId, $name) {
    $imagePath = "./img/pokemons/{$pokedexId}_$name.png";

    // Use file_get_contents and file_put_contents to download and save the image
    $imageData = file_get_contents($imageUrl);
    if ($imageData !== false && !file_exists($imagePath)) {
        file_put_contents($imagePath, $imageData);
    }

    return $imageData !== false ? $imagePath : null;
}

function downloadTypeImage($imageUrl, $name) {
    $imagePath = "./img/types/$name.png";

    // Use file_get_contents and file_put_contents to download and save the image
    $imageData = file_get_contents($imageUrl);
    if ($imageData !== false && !file_exists($imagePath)) {
        file_put_contents($imagePath, $imageData);
    }

    return $imageData !== false ? $imagePath : null;
}

