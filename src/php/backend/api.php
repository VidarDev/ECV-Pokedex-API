<?php

class APIPokemon {
    public function __construct() {
    }

    public function connexion() {
        // Recupère l'URL de API dans le .env
        $apiUrl = $_ENV['API_URL'];

        return $apiUrl;
    }

    public function JSON($jsonResponse) {
        // Décoder la réponse JSON en tableau PHP
        $data = json_decode($jsonResponse, true);

        return $data;
    }

    public function formatTypeData($data) {

        $extractedPokemonData = [
            'typeId' => isset($data['id']) ? $data['id'] : null,
            'typeName' => isset($data['name']) ? $data['name'] : null,
            'typeImage' => isset($data['image']) ? $data['image'] : null,
            'typeEnglishName' => isset($data['englishName']) ? $data['englishName'] : null,
        ];

        return $extractedPokemonData;
    }

    public function formatPokemonData($data) {

        $extractedPokemonData = [
            'pokemonId' => isset($data['pokedexId']) ? $data['pokedexId'] : null,
            'pokemonName' => isset($data['name']) ? $data['name'] : null,
            'pokemonImage' => isset($data['image']) ? $data['image'] : null,
            'pokemonStats' => isset($data['stats']) ? $data['stats'] : null,
            'pokemonGeneration' => isset($data['apiGeneration']) ? $data['apiGeneration'] : null,
        ];

        $firstType = $data['apiTypes'][0];
        $secondType = $data['apiTypes'][1] ?? null;
        $extractedPokemonData['pokemonTypes']['firstName'] = isset($firstType['name']) ? $firstType['name'] : null;
        $extractedPokemonData['pokemonTypes']['firstImage'] = isset($firstType['image']) ? $firstType['image'] : null;
        $extractedPokemonData['pokemonTypes']['secondName'] = isset($secondType['name']) ? $secondType['name'] : null;
        $extractedPokemonData['pokemonTypes']['secondImage'] = isset($secondType['image']) ? $secondType['image'] : null;

        $firstEvolution = $data['apiEvolutions'][0] ?? null;
        $extractedPokemonData['pokemonNextEvolId'] = isset($firstEvolution['pokedexId']) ? $firstEvolution['pokedexId'] : null;
        $extractedPokemonData['pokemonNextEvolName'] = isset($firstEvolution['name']) ? $firstEvolution['name'] : null;

        $extractedPokemonData['pokemonPrevEvolId'] = isset($data['apiPreEvolution']['pokedexIdd']) ? $data['apiPreEvolution']['pokedexIdd'] : null;
        $extractedPokemonData['pokemonPrevEvolName'] = isset($data['apiPreEvolution']['name']) ? $data['apiPreEvolution']['name'] : null;

        return $extractedPokemonData;
    }

    public function getPokemonByIdOrName($input) {
        $formatInput = formatString($input);

        // Déterminer si l'entrée est un ID ou un nom
        $isId = is_numeric($formatInput);

        // Essayer de récupérer le Pokémon de la base de données
        $pokemon = $isId ? $this->getPokemonById($formatInput) : $this->getPokemonByName($formatInput);

        return $pokemon;
    }

    public function getPokemonById($pokedexID) {
        $apiUrl = $this->connexion();

        // GET request
        $reponse = file_get_contents("{$apiUrl}/pokemon/{$pokedexID}");

        $extractedData = $this->formatPokemonData($this->JSON($reponse));
        return $extractedData;
    }

    public function getPokemonByName($name) {
        $apiUrl = $this->connexion();

        // GET request
        $reponse = file_get_contents("{$apiUrl}/pokemon/{$name}");

        $extractedData = $this->formatPokemonData($this->JSON($reponse));
        return $extractedData;
    }

    public function getTypesAll() {
        $apiUrl = $this->connexion();

        // GET request
        $reponse = file_get_contents("{$apiUrl}/types");
        $reponseDecoded = $this->JSON($reponse);

        $extractedData =[];

        if ($reponseDecoded && is_array($reponseDecoded)) {
            foreach ($reponseDecoded as $data) {
                $extractedData[] = ($this->formatTypeData($data));
            };
        }

        return $extractedData;
    }

    public function getPokemonsAll() {
        $apiUrl = $this->connexion();

        // GET request
        $reponse = file_get_contents("{$apiUrl}/pokemon");
        $reponseDecoded = $this->JSON($reponse);

        $extractedData =[];

        if ($reponseDecoded && is_array($reponseDecoded)) {
            foreach ($reponseDecoded as $data) {
                $extractedData[] = ($this->formatPokemonData($data));
            };
        }

        return $extractedData;
    }
}

?>