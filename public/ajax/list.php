<?php
    include __DIR__ . '/../../src/php/backend/database.php';

    $dao = new DAO();

    // Assurez-vous que les données sont envoyées en POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Récupérer les données JSON envoyées
        $inputJSON = file_get_contents('php://input');
        $params = json_decode($inputJSON, true); // Convertir en tableau associatif

        if($params['generation'] !== '0' && $params['type'] !== '0') {
            $responseSql = $dao->getListPokemons($params['generation'], $params['type'], $params['page']);
        } else {
            if ($params['generation'] === '0' && $params['type'] !== '0') {
                $responseSql = $dao->getListPokemonsWithType($params['type'], $params['page']);
            }
            elseif ($params['generation'] !== '0' && $params['type'] === '0') {
                $responseSql = $dao->getListPokemonsWithGeneration($params['generation'], $params['page']);
            }
            else {
                $responseSql = $dao->getListPokemonsWithoutTypeAndGeneration($params['page']);
            }
        }


        foreach ($responseSql as $pokemon) {
            $pokemonUser = $dao->getPokemonById($pokemon['id']);
            $response[] = $dao->UIPokemonCard($pokemonUser);
        }

        // Envoyer une réponse JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
