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
            'id_pokedex' => isset($data['pokedexId']) ? $data['pokedexId'] : null,
            'name' => isset($data['name']) ? $data['name'] : null,
            'image' => isset($data['image']) ? $data['image'] : null,
            'stats' => isset($data['stats']) ? $data['stats'] : null,
            'generation' => isset($data['apiGeneration']) ? $data['apiGeneration'] : null,
        ];

        $typeCount = count($data['apiTypes'] ?? []);
        if ($typeCount > 0) {
            $extractedData['id_types_name_1'] = $data['apiTypes'][0]['name'];
            $extractedData['id_types_image_1'] = $data['apiTypes'][0]['image'];
            if ($typeCount > 1) {
                $extractedData['id_types_name_2'] = $data['apiTypes'][1]['name'];
                $extractedData['id_types_image_2'] = $data['apiTypes'][1]['image'];
            } else {
                $extractedData['id_types_name_2'] = null;
                $extractedData['id_types_image_2'] = null;
            }
        } else {
            $extractedData['id_types_name_1'] = null;
            $extractedData['id_types_image_1'] = null;
            $extractedData['id_types_name_2'] = null;
            $extractedData['id_types_image_2'] = null;
        }

        // Aplatir les évolutions (notez que cela ne fonctionnera que pour une seule évolution)
        if (!empty($data['apiEvolutions'])) {
            $firstEvolution = $data['apiEvolutions'][0];
            $extractedData['evolution_name'] = $firstEvolution['name'];
            $extractedData['evolution_id_pokedex'] = $firstEvolution['pokedexId'];
        } else {
            $extractedData['evolution_name'] = null;
            $extractedData['evolution_id_pokedex'] = null;
        }

        // Aplatir les évolutions (notez que cela ne fonctionnera que pour une seule évolution)
        if (!empty($data['apiPreEvolution']) && is_array($data['apiPreEvolution'])) {
            $extractedData['pre_evolution_name'] = $data['apiPreEvolution']['name'] ?? null;
            $extractedData['pre_evolution_id_pokedex'] = $data['apiPreEvolution']['pokedexIdd'] ?? null;
        } else {
            $extractedData['pre_evolution_name'] = null;
            $extractedData['pre_evolution_id_pokedex'] = null;
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