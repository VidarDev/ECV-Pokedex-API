<?php

class APIPokemon {
    public function __construct() {
    }

    public function connexion()
    {
        $API_URL = $_ENV['API_URL']; // Get API_URL in .env
        return $API_URL;
    }

    public function JSON($jsonResponse)
    {
        // Décoder la réponse JSON en tableau PHP
        $data = json_decode($jsonResponse, true);
        return $data;
    }

    public function formatTypeData($data)
    {
        $extractedPokemonData = [
            'typeId' => isset($data['id']) ? $data['id'] : null,
            'typeName' => isset($data['name']) ? $data['name'] : null,
            'typeImage' => isset($data['image']) ? $data['image'] : null,
            'typeEnglishName' => isset($data['englishName']) ? $data['englishName'] : null,
        ];
        return $extractedPokemonData;
    }

    public function formatPokemonData($data)
    {
        $extractedPokemonData = [
            'pokemonId' => isset($data['pokedexId']) ? $data['pokedexId'] : null,
            'pokemonName' => isset($data['name']) ? $data['name'] : null,
            'pokemonImage' => isset($data['image']) ? $data['image'] : null,
            'pokemonStats' => isset($data['stats']) ? $data['stats'] : null,
            'pokemonGeneration' => isset($data['apiGeneration']) ? $data['apiGeneration'] : null,
        ];

        $firstType = $data['apiTypes'][0] ?? null;
        $secondType = $data['apiTypes'][1] ?? null;


        // First check if $secondType is not null
        if (isset($secondType))
        {
            switch ($firstType['name'])
            {
                case "Poison":
                    // Logic for Poison and secondType non-Ground-  ERROR FROM API
                    if ($secondType['name'] !== "Sol") {
                        $extractedPokemonData['pokemonTypes']['firstName'] = isset($secondType['name']) ? $secondType['name'] : null;
                        $extractedPokemonData['pokemonTypes']['firstImage'] = isset($secondType['image']) ? $secondType['image'] : null;
                        $extractedPokemonData['pokemonTypes']['secondName'] = isset($firstType['name']) ? $firstType['name'] : null;
                        $extractedPokemonData['pokemonTypes']['secondImage'] = isset($firstType['image']) ? $firstType['image'] : null;
                    } else {
                        $extractedPokemonData['pokemonTypes']['firstName'] = isset($firstType['name']) ? $firstType['name'] : null;
                        $extractedPokemonData['pokemonTypes']['firstImage'] = isset($firstType['image']) ? $firstType['image'] : null;
                        $extractedPokemonData['pokemonTypes']['secondName'] = isset($secondType['name']) ? $secondType['name'] : null;
                        $extractedPokemonData['pokemonTypes']['secondImage'] = isset($secondType['image']) ? $secondType['image'] : null;
                    }
                    break;

                case "Vol":
                    // Logic for Flight and non-Normal secondType - ERROR FROM API
                    if ($secondType['name'] !== "Normal") {
                        $extractedPokemonData['pokemonTypes']['firstName'] = isset($secondType['name']) ? $secondType['name'] : null;
                        $extractedPokemonData['pokemonTypes']['firstImage'] = isset($secondType['image']) ? $secondType['image'] : null;
                        $extractedPokemonData['pokemonTypes']['secondName'] = isset($firstType['name']) ? $firstType['name'] : null;
                        $extractedPokemonData['pokemonTypes']['secondImage'] = isset($firstType['image']) ? $firstType['image'] : null;
                    } else {
                        $extractedPokemonData['pokemonTypes']['firstName'] = isset($firstType['name']) ? $firstType['name'] : null;
                        $extractedPokemonData['pokemonTypes']['firstImage'] = isset($firstType['image']) ? $firstType['image'] : null;
                        $extractedPokemonData['pokemonTypes']['secondName'] = isset($secondType['name']) ? $secondType['name'] : null;
                        $extractedPokemonData['pokemonTypes']['secondImage'] = isset($secondType['image']) ? $secondType['image'] : null;
                    }
                    break;

                default:
                    $extractedPokemonData['pokemonTypes']['firstName'] = isset($firstType['name']) ? $firstType['name'] : null;
                    $extractedPokemonData['pokemonTypes']['firstImage'] = isset($firstType['image']) ? $firstType['image'] : null;
                    $extractedPokemonData['pokemonTypes']['secondName'] = isset($secondType['name']) ? $secondType['name'] : null;
                    $extractedPokemonData['pokemonTypes']['secondImage'] = isset($secondType['image']) ? $secondType['image'] : null;
                    break;
            }
        } else
        {
            $extractedPokemonData['pokemonTypes']['firstName'] = isset($firstType['name']) ? $firstType['name'] : null;
            $extractedPokemonData['pokemonTypes']['firstImage'] = isset($firstType['image']) ? $firstType['image'] : null;
            $extractedPokemonData['pokemonTypes']['secondName'] = isset($secondType['name']) ? $secondType['name'] : null;
            $extractedPokemonData['pokemonTypes']['secondImage'] = isset($secondType['image']) ? $secondType['image'] : null;
        }

        $firstEvolution = $data['apiEvolutions'][0] ?? null;
        $extractedPokemonData['pokemonNextEvolId'] = isset($firstEvolution['pokedexId']) ? $firstEvolution['pokedexId'] : null;

        $extractedPokemonData['pokemonPrevEvolId'] = isset($data['apiPreEvolution']['pokedexIdd']) ? $data['apiPreEvolution']['pokedexIdd'] : null;

        return $extractedPokemonData;
    }

    public function getPokemonByIdOrName($input)
    {
        $formatInput = formatString($input);

        // Determine if the entry is an ID or a name
        $isId = is_numeric($formatInput);

        // Try to retrieve the Pokémon from the database
        $pokemon = $isId ? $this->getPokemonById($formatInput) : $this->getPokemonByName($formatInput);

        return $pokemon;
    }

    public function getPokemonById($pokedexID)
    {
        $API_URL = $this->connexion();

        // Create an HTTP flow context to handle 404 errors
        $context = stream_context_create([
            "http" => [
                "method" => "GET",
                "header" => "Accept-language: en\r\n" .
                    "Cookie: foo=bar\r\n"
            ]
        ]);

        // GET request
        $jsonResponse = @file_get_contents("{$API_URL}/pokemon/{$pokedexID}", false, $context);

        if (!$jsonResponse) return null;

        $extractedData = $this->formatPokemonData($this->JSON($jsonResponse));
        return $extractedData;
    }

    public function getPokemonByName($name) {
        $API_URL = $this->connexion();

        // Create an HTTP flow context to handle 404 errors
        $context = stream_context_create([
            "http" => [
                "method" => "GET",
                "header" => "Accept-language: en\r\n" .
                    "Cookie: foo=bar\r\n"
            ]
        ]);

        // GET request
        $jsonResponse = @file_get_contents("{$API_URL}/pokemon/{$name}", false, $context);

        if (!$jsonResponse) return null;

        $extractedData = $this->formatPokemonData($this->JSON($jsonResponse));
        return $extractedData;
    }

    public function getAllTypes()
    {
        $API_URL = $this->connexion();

        // GET request
        $jsonResponse = file_get_contents("{$API_URL}/types");
        $reponse = $this->JSON($jsonResponse);

        $extractedData =[];

        if ($reponse && is_array($reponse)) {
            foreach ($reponse as $data) {
                $extractedData[] = ($this->formatTypeData($data));
            };
        }

        return $extractedData;
    }

    public function getAllPokemons()
    {
        $API_URL = $this->connexion();

        // GET request
        $jsonResponse = file_get_contents("{$API_URL}/pokemon");
        $reponse = $this->JSON($jsonResponse);

        $extractedData =[];

        if ($reponse && is_array($reponse)) {
            foreach ($reponse as $data) {
                $extractedData[] = ($this->formatPokemonData($data));
            };
        }

        return $extractedData;
    }
}

?>