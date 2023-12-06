<?php

class APIPokemon {
    public function __construct() {
    }

    public function connexion() {
        $apiUrl = $_ENV['API_URL'];
        return $apiUrl;
    }

    public function JSON($jsonResponse) {
        // Décoder la réponse JSON en tableau PHP
        $data = json_decode($jsonResponse, true);

        // Extraire les données requises
        $extractedData = [
            'pokedexId' => isset($data['pokedexId']) ? $data['pokedexId'] : null,
            'name' => isset($data['name']) ? $data['name'] : null,
            'image' => isset($data['image']) ? $data['image'] : null,
            'stats' => isset($data['stats']) ? $data['stats'] : null,
            'apiTypes' => array_map(function ($type) {
                return ['name' => $type['name'], 'image' => $type['image']];
            }, isset($data['apiTypes']) ? $data['apiTypes'] : []),
            'apiGeneration' => isset($data['apiGeneration']) ? $data['apiGeneration'] : null,
        ];

        // Aplatir les évolutions (notez que cela ne fonctionnera que pour une seule évolution)
        if (!empty($data['apiEvolutions'])) {
            $firstEvolution = $data['apiEvolutions'][0];
            $extractedData['evolution_name'] = $firstEvolution['name'];
            $extractedData['evolution_pokedexId'] = $firstEvolution['pokedexId'];
        } else {
            $extractedData['evolution_name'] = null;
            $extractedData['evolution_pokedexId'] = null;
        }

        // Aplatir les évolutions (notez que cela ne fonctionnera que pour une seule évolution)
        if (!empty($data['apiPreEvolutions'])) {
            $firstEvolution = $data['apiPreEvolutions'][0];
            $extractedData['pre_evolution_name'] = $firstEvolution['name'];
            $extractedData['pre_evolution_pokedexId'] = $firstEvolution['pokedexId'];
        } else {
            $extractedData['pre_evolution_name'] = null;
            $extractedData['pre_evolution_pokedexId'] = null;
        }

        return $extractedData;
    }

    public function getPokemonByPokedexID($pokedexID) {
        $apiUrl = $this->connexion();

        $reponseData = file_get_contents("{$apiUrl}/pokemon/{$pokedexID}");

        $extractedData = $this->JSON($reponseData);
        return $extractedData;
    }

    public function getPokemonByName($name) {
        $apiUrl = $this->connexion();

        $reponseData = file_get_contents("{$apiUrl}/pokemon/{$name}");

        $extractedData = $this->JSON($reponseData);
        return $extractedData;
    }

    public function getTypes() {
        $apiUrl = $this->connexion();

        $reponseData = file_get_contents("{$apiUrl}/types/");
        $datas = json_decode($reponseData, true);

        $extractedData = [];
        $i = 0;

        if ($datas && is_array($datas)) {
            foreach ($datas as $data) {
                $extractedData[$i]['image'] = $data['image'];
                $extractedData[$i]['name'] = $data['name'];

                $i++;
            }
        }

        return $extractedData;
    }

//    public function getPokemonAll() {
//
//    }

//    public function getPokemonByTypes() {
//
//    }
}

?>