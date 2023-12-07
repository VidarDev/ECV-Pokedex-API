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

        $typeCount = count($data['apiTypes'] ?? []);
        if ($typeCount > 0) {
            $firstType = $data['apiTypes'][0];
            $secondType = $data['apiTypes'][1] ?? null;

            $extractedPokemonData['pokemonTypes']['fist_name'] = isset($firstType['name']) ? $firstType['name'] : null;
            $extractedPokemonData['pokemonTypes']['fist_image'] = isset($firstType['image']) ? $firstType['image'] : null;
            $extractedPokemonData['pokemonTypes']['second_name'] = isset($secondType['name']) ? $secondType['name'] : null;
            $extractedPokemonData['pokemonTypes']['second_image'] = isset($secondType['image']) ? $secondType['image'] : null;

        }

        $nextEvolutionCount = count($data['apiEvolutions'] ?? []);
        if ($nextEvolutionCount > 0) {
            $firstEvolution = $data['apiEvolutions'][0];

            $extractedPokemonData['pokemonNextEvolId'] = isset($firstEvolution['pokedexId']) ? $firstEvolution['pokedexId'] : null;
            $extractedPokemonData['pokemonNextEvolName'] = isset($firstEvolution['name']) ? $firstEvolution['name'] : null;
        }

        if (!empty($data['apiPreEvolution']) && is_array($data['apiPreEvolution'])) {
            $extractedPokemonData['pokemonPrevEvolId'] = $data['apiPreEvolution']['pokedexIdd'] ?? null;
            $extractedPokemonData['pokemonPrevEvolName'] = $data['apiPreEvolution']['name'] ?? null;
        }

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