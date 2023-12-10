<?php
include __DIR__ . '/config.php';
include __DIR__ . '/api.php';
include __DIR__ . '/functions.php';

class DAO {

    public function __construct()
    {
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
            header('Location: /error.php'); // Redirect to an error page
            die("Database connection error: " . $e->getMessage());
        }

        return $pdo;
    }

    public function checkIfTypesExists(): bool
    {
        $pdo = $this->connexion();

        $query = "SELECT id_type from `dex_types`;";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $this->addAllTypes();
            return false;
        }
        return true;
    }

    public function checkIfPokemonsExists(): bool
    {
        $pdo = $this->connexion();

        $query = "SELECT id from `dex_pokemons`;";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) return false;
        return true;
    }

    // Single-use function: Its purpose is to initialize the database with a certain number of pokemons
    public function addPokemonsInit($count)
    {
        set_time_limit(500); // Increases runtime limit to 500 seconds

        for ($i = 1; $i < $count + 1; $i++) {
            $pokemon=$this->api->getPokemonById($i);
            $this->addPokemon($pokemon);
        }
    }

    public function addPokemon($pokemonData)
    {
        $pdo = $this->connexion();

        try {
            $pdo->beginTransaction();

            $pokemonId = $pokemonData['pokemonId'];

            // Download the Pokémon image
            $imagePath = downloadPokemonImage($pokemonData['pokemonImage'], $pokemonData['pokemonId'], $pokemonData['pokemonName']);

            // Inserting general Pokémon information
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

            // Inserting statistics
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

            // Inserting types
            $query = "
                INSERT INTO `dex_pokemons_types` (id_pokemon, id_type_first, id_type_second) 
                VALUES (?, ?, ?);
            ";

            $typeFirstId = $this->getIdTypeByName($pokemonData['pokemonTypes']['firstName']);
            $typeSecondId = $this->getIdTypeByName($pokemonData['pokemonTypes']['secondName']);

            $stmt = $pdo->prepare($query);
            $stmt->execute([$pokemonId, $typeFirstId, $typeSecondId]);

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function addAllTypes()
    {
        $pdo = $this->connexion();

        try {
            $pdo->beginTransaction();

            $types = $this->api->getAllTypes();
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

    public function getPokemonByIdOrName($input)
    {
        $formatInput = formatString($input);

        // Determine if the entry is an ID or a name
        $isId = is_numeric($formatInput);

        // Try to retrieve the Pokémon from the database
        $pokemon = $isId ?
            $this->getPokemonById($formatInput) :
            $this->getPokemonByName($formatInput);

        // If the Pokémon is not found in the database, retrieve it via the API
        if (!$pokemon) {
            $pokemonData = $isId ?
                $this->api->getPokemonById($formatInput) :
                $this->api->getPokemonByName($formatInput);

            if ($pokemonData) {
                $this->addPokemon($pokemonData);
                $pokemon = $isId ?
                    $this->getPokemonById($formatInput) :
                    $this->getPokemonByName($formatInput);
            }
        }

        return $pokemon;
    }

    public function getPokemonById($pokedexID)
    {
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

    public function getPokemonByName($name)
    {
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

    public function getPokemonEvolutionById($pokedexID)
    {
        $pdo = $this->connexion();

        $query = "SELECT name, image FROM `dex_pokemons` WHERE id = ?;";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$pokedexID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getListPokemons($generation, $type, $page)
    {
        $pdo = $this->connexion();

        $displayLimit = 24;
        $limit = $displayLimit + 1;
        $offset = ($page - 1) * $displayLimit;

        $query = "
            SELECT 
                p.id 
            FROM 
                `dex_pokemons` p 
                LEFT JOIN `dex_pokemons_types` pt ON p.id = pt.id_pokemon
            WHERE 
                p.generation = :generation AND
                (pt.id_type_first = :type OR pt.id_type_second = :type)
            GROUP BY 
                p.id 
            ORDER BY 
                p.id 
            LIMIT :limit 
            OFFSET :offset ;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':generation', $generation, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getListPokemonsWithType($type, $page)
    {
        $pdo = $this->connexion();

        $displayLimit = 24;
        $limit = $displayLimit + 1;
        $offset = ($page - 1) * $displayLimit;

        $query = "
            SELECT 
                p.id 
            FROM 
                `dex_pokemons` p 
                LEFT JOIN `dex_pokemons_types` pt ON p.id = pt.id_pokemon
            WHERE 
                pt.id_type_first = :type OR pt.id_type_second = :type
            GROUP BY 
                p.id 
            ORDER BY 
                p.id 
            LIMIT :limit 
            OFFSET :offset ;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':type', $type, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getListPokemonsWithGeneration($generation, $page)
    {
        $pdo = $this->connexion();

        $displayLimit = 24;
        $limit = $displayLimit + 1;
        $offset = ($page - 1) * $displayLimit;

        $query = "SELECT p.id FROM `dex_pokemons` p WHERE p.generation = :generation GROUP BY p.id ORDER BY p.id LIMIT :limit OFFSET :offset ;";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':generation', $generation, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getListPokemonsWithoutTypeAndGeneration($page)
    {
        $pdo = $this->connexion();

        $displayLimit = 24;
        $limit = $displayLimit + 1;
        $offset = ($page - 1) * $displayLimit;

        $query = "SELECT p.id FROM `dex_pokemons` p GROUP BY p.id ORDER BY p.id LIMIT :limit OFFSET :offset;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getIdRandomOfPokemon()
    {
        $pdo = $this->connexion();

        $query = "SELECT id FROM `dex_pokemons` ORDER BY RAND () LIMIT 1;";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['id'];
    }

    public function getIdTypeByName($name)
    {
        $pdo = $this->connexion();

        $query = "SELECT id_type from `dex_types` where name_FR = ?";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$name]);
        $reponse = $stmt->fetch(PDO::FETCH_ASSOC);

        return $reponse['id_type'] ?? null;
    }

    public function getAllGenerations()
    {
        $pdo = $this->connexion();

        $query = "SELECT DISTINCT generation FROM `dex_pokemons` ORDER BY generation ASC;";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllTypes()
    {
        $pdo = $this->connexion();

        $query = "SELECT DISTINCT id_type, name_FR, image FROM `dex_types` ORDER BY id_type ASC;";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCardPokemon($pokedexID)
    {
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

    public function UIPokemonCard($pokemonCard): string
    {
        $formatPokedexId = formatPokedexId($pokemonCard['id']);

        if(isset($pokemonCard['id_type_second'])){
            $code = "
                <a class='pokemon-card {$pokemonCard['pokemon_type_first_name_EN']}' href='?id={$pokemonCard['id']}'>
                    <div class='pokemon-card-top'>
                        <span class='pokemon-card-top__name'>{$pokemonCard['name']}</span>
                        <span class='pokemon-card-top__pokedex'>#$formatPokedexId</span>
                    </div>
                    <div class='pokemon-card-bottom'>
                        <div class='pokemon-card-bottom__types'>
                            <img src='{$pokemonCard['pokemon_type_first_image']}' role='img' alt='{$pokemonCard['pokemon_type_first_name_FR']}' title='{$pokemonCard['pokemon_type_first_name_FR']}' aria-label='{$pokemonCard['pokemon_type_first_name_FR']}' loading='lazy' width='200' height='200'/>
                            <img src='{$pokemonCard['pokemon_type_second_image']}' role='img' alt='{$pokemonCard['pokemon_type_second_name_FR']}' title='{$pokemonCard['pokemon_type_second_name_FR']}' aria-label='{$pokemonCard['pokemon_type_second_name_FR']}' loading='lazy' width='200' height='200'/>
                        </div>
                        <img class='pokemon-card-bottom__image' src='{$pokemonCard['image']}' role='img' alt='{$pokemonCard['name']}' title='{$pokemonCard['name']}' aria-label='{$pokemonCard['name']}' loading='lazy' width='200' height='200'/>
                    </div>
                </a>
            ";
        }
        else {
            $code = "
                <a class='pokemon-card {$pokemonCard['pokemon_type_first_name_EN']}' href='?id={$pokemonCard['id']}'>
                    <div class='pokemon-card-top'>
                        <span class='pokemon-card-top__name'>{$pokemonCard['name']}</span>
                        <span class='pokemon-card-top__pokedex'>#$formatPokedexId</span>
                    </div>
                    <div class='pokemon-card-bottom'>
                        <div class='pokemon-card-bottom__types'>
                            <img src='{$pokemonCard['pokemon_type_first_image']}' role='img' alt='{$pokemonCard['pokemon_type_first_name_FR']}' title='{$pokemonCard['pokemon_type_first_name_FR']}' aria-label='{$pokemonCard['pokemon_type_first_name_FR']}' loading='lazy' width='200' height='200'/>
                        </div>
                        <img class='pokemon-card-bottom__image' src='{$pokemonCard['image']}' role='img' alt='{$pokemonCard['name']}' title='{$pokemonCard['name']}' aria-label='{$pokemonCard['name']}' loading='lazy' width='200' height='200'/>
                    </div>
                </a>
            ";
        }

        return $code;
    }

}

