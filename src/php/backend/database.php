<?php
include __DIR__ . '/config.php';
include __DIR__ . '/api.php';
include __DIR__ . '/functions.php';

class DAO {

    public function __construct() {
        $this->api = new APIPokemon();
    }

    public function connexion()
    {
        // SQL variables
        $dsn = "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];

        // SQL connection
        try {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            header('Location: /error.php'); // Redirection vers une page d'erreur
            die("Database connection error: " . $e->getMessage());
        }

        return $pdo;
    }

    public function checkTypesExists() {
        $pdo = $this->connexion();

        $query = "SELECT id_type from dex_types;";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            $this->addTypesAll();
        }
    }

    public function checkPokemonsExists() {
        $pdo = $this->connexion();

        $query = "SELECT id from dex_pokemons;";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getPokemonByIdOrName($input) {
        $formatInput = formatString($input);

        // Déterminer si l'entrée est un ID ou un nom
        $isId = is_numeric($formatInput);

        // Essayer de récupérer le Pokémon de la base de données
        $pokemon = $isId ? $this->getPokemonById($formatInput) : $this->getPokemonByName($formatInput);

        // Si le Pokémon n'est pas trouvé en base de données, le récupérer via l'API
        if (!$pokemon) {
            $pokemonData = $isId ? $this->api->getPokemonById($formatInput) : $this->api->getPokemonByName($formatInput);
            if ($pokemonData) {
                $this->addPokemon($pokemonData);
                $pokemon = $isId ? $this->getPokemonById($formatInput) : $this->getPokemonByName($formatInput);
            }
        }

        return $pokemon;
    }

    public function getRandomPokemonID() {
        $pdo = $this->connexion();

        $query = "SELECT id FROM `dex_pokemons` ORDER BY RAND () LIMIT 1;";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['id'];
    }

    public function getGenerations() {
        $pdo = $this->connexion();

        $query = "SELECT DISTINCT generation FROM `dex_pokemons` ORDER BY generation ASC;";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getEvolutionById($pokedexID) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.name,
                p.image
            FROM 
                `dex_pokemons` p
            WHERE 
                p.id = ?;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$pokedexID]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPokemonById($pokedexID) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.id,
                p.name,
                p.image,
                p.generation,
                p.id_next_evolution,
                p.id_prev_evolution,
                ps.hp,
                ps.attack,
                ps.defense,
                ps.special_attack,
                ps.special_defense,
                ps.speed,
                pt.id_type_first,
                pt.id_type_second,
                t1.name_FR AS pokemon_type_first_name_FR,
                t1.name_EN AS pokemon_type_first_name_EN,
                t1.image AS pokemon_type_first_image,
                t2.name_FR AS pokemon_type_second_name_FR,
                t2.image AS pokemon_type_second_image
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemons_stats` ps ON p.id = ps.id_pokemon
                LEFT JOIN `dex_pokemons_types` pt ON p.id = pt.id_pokemon
                LEFT JOIN `dex_types` t1 ON pt.id_type_first = t1.id_type
                LEFT JOIN `dex_types` t2 ON pt.id_type_second = t2.id_type
            WHERE 
                p.id = ?
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$pokedexID]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPokemonByName($name) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.id,
                p.name,
                p.image,
                p.generation,
                p.id_next_evolution,
                p.id_prev_evolution,
                ps.hp,
                ps.attack,
                ps.defense,
                ps.special_attack,
                ps.special_defense,
                ps.speed,
                pt.id_type_first,
                pt.id_type_second,
                t1.name_FR AS pokemon_type_first_name_FR,
                t1.name_EN AS pokemon_type_first_name_EN,
                t1.image AS pokemon_type_first_image,
                t2.name_FR AS pokemon_type_second_name_FR,
                t2.image AS pokemon_type_second_image
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemons_stats` ps ON p.id = ps.id_pokemon
                LEFT JOIN `dex_pokemons_types` pt ON p.id = pt.id_pokemon
                LEFT JOIN `dex_types` t1 ON pt.id_type_first = t1.id_type
                LEFT JOIN `dex_types` t2 ON pt.id_type_second = t2.id_type
            WHERE 
                p.name = ?
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$name]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPokemonIdByGeneration($generation) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.id
            FROM 
                `dex_pokemons` p
            WHERE 
                p.generation = ?
            GROUP BY 
                p.id
            ORDER BY
                p.id
            LIMIT 25;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$generation]);

        return $stmt->fetchAll();
    }

    public function getPokemonCard($pokedexID) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.id,
                p.name,
                p.image,
                pt.id_type_first,
                pt.id_type_second,
                t1.name_FR AS pokemon_type_first_name_FR,
                t1.name_EN AS pokemon_type_first_name_EN,
                t1.image AS pokemon_type_first_image,
                t2.name_FR AS pokemon_type_second_name_FR,
                t2.image AS pokemon_type_second_image
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemons_types` pt ON p.id = pt.id_pokemon
                LEFT JOIN `dex_types` t1 ON pt.id_type_first = t1.id_type
                LEFT JOIN `dex_types` t2 ON pt.id_type_second = t2.id_type
            WHERE 
                p.id = ?
            GROUP BY 
                p.id;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$pokedexID]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTypeIdByName($name) {
        $pdo = $this->connexion();

        $query = "SELECT id_type from `dex_types` where name_FR = ?";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$name]);

        $reponse = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = isset($reponse['id_type']) ? $reponse['id_type'] : null;

        return $result;
    }

    public function addTypesAll() {
        $pdo = $this->connexion();

        try {
            $pdo->beginTransaction();

            $types = $this->api->getTypesAll();

            foreach($types as $type) {

                $imagePath = downloadTypeImage($type['typeImage'], $type['typeName']);

                $query = "INSERT INTO `dex_types` (id_type, name_FR, name_EN, image) VALUES (?, ?, ?, ?)";

                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    $type['typeId'],
                    $type['typeName'],
                    $type['typeEnglishName'],
                    $imagePath
                ]);
            }

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function addPokemonsAll() {
        set_time_limit(500); // Augmente la limite à 500 secondes

        $pokemons = $this->api->getPokemonsAll();

        foreach($pokemons as $pokemon) {
            $this->addPokemon($pokemon);
        }
    }

    public function addPokemon($pokemonData) {
        $pdo = $this->connexion();

        try {
            $pdo->beginTransaction();

            $pokemonId = $pokemonData['pokemonId'];

            // Télécharger l'image du Pokémon
            $imagePath = downloadPokemonImage($pokemonData['pokemonImage'], $pokemonData['pokemonId'], $pokemonData['pokemonName']);

            // Insertion des informations générales du Pokémon
            $query = "
                INSERT INTO `dex_pokemons` (id, name, image, generation, id_next_evolution, id_prev_evolution) 
                VALUES (?, ?, ?, ?, ?, ?);
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                $pokemonData['pokemonName'],
                $imagePath,
                $pokemonData['pokemonGeneration'],
                $pokemonData['pokemonNextEvolId'],
                $pokemonData['pokemonPrevEvolId']
            ]);

            // Insertion des statistiques
            $query = "
                INSERT INTO `dex_pokemons_stats` (id_pokemon, hp, attack, defense, special_attack, special_defense, speed) 
                VALUES (?, ?, ?, ?, ?, ?, ?);
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                $pokemonData['pokemonStats']['HP'],
                $pokemonData['pokemonStats']['attack'],
                $pokemonData['pokemonStats']['defense'],
                $pokemonData['pokemonStats']['special_attack'],
                $pokemonData['pokemonStats']['special_defense'],
                $pokemonData['pokemonStats']['speed']
            ]);

            // Insertion des types
            $query = "
                INSERT INTO `dex_pokemons_types` (id_pokemon, id_type_first, id_type_second) 
                VALUES (?, ?, ?);
            ";

            $typeFirstId = $this->getTypeIdByName($pokemonData['pokemonTypes']['firstName']);
            $typeSecondId = $this->getTypeIdByName($pokemonData['pokemonTypes']['secondName']);

            $stmt = $pdo->prepare($query);
            $stmt->execute([$pokemonId, $typeFirstId, $typeSecondId]);

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function UIPokemonCard($pokemon) {
        $formatPokedexId = formatPokedexId($pokemon['id']);

        return "
            <div class='pokemon-card {$pokemon['pokemon_type_first_name_EN']}'>
                <div class='pokemon-card-top'>
                    <span class='pokemon-card-top__name'>{$pokemon['name']}</span>
                    <span class='pokemon-card-top__pokedex'>#{$formatPokedexId}</span>
                </div>
                <div class='pokemon-card-bottom'>
                    <div class='pokemon-card-bottom__types'>
                        <img src='{$pokemon['pokemon_type_first_image']}' role='img' alt='{$pokemon['pokemon_type_first_name_FR']}' title='{$pokemon['pokemon_type_first_name_FR']}' aria-label='{$pokemon['pokemon_type_first_name_FR']}' loading='lazy' width='200' height='200'/>
                        <img src='{$pokemon['pokemon_type_second_image']}' role='img' alt='{$pokemon['pokemon_type_second_name_FR']}' title='{$pokemon['pokemon_type_second_name_FR']}' aria-label='{$pokemon['pokemon_type_second_name_FR']}' loading='lazy' width='200' height='200'/>
                    </div>
                    <img class='pokemon-card-bottom__image' src='{$pokemon['image']}' role='img' alt='{$pokemon['name']}' title='{$pokemon['name']}' aria-label='{$pokemon['name']}' loading='lazy' width='200' height='200'/>
                </div>
            </div>
        ";
    }
}

?>