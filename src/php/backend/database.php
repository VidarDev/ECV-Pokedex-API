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
            die("Database connection error: " . $e->getMessage());
        }

        return $pdo;
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

    public function getPokemonById($pokedexID) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.id_pokedex AS pokemonId,
                p.name AS pokemonName,
                p.image AS pokemonImage,
                p.generation AS pokemonGeneration,
                ps.hp AS pokemonStatsHp,
                ps.attack AS pokemonStatsAttack,
                ps.defense AS pokemonStatsDefense,
                ps.special_attack AS pokemonStatsSpecialAttack,
                ps.special_defense AS pokemonStatsSpecialDefense,
                ps.speed AS pokemonStatsSpeed,
                pe.evolution_id_pokedex AS pokemonNextEvolId,
                pe.evolution_name AS pokemonNextEvolName,
                pp.pre_evolution_id_pokedex AS pokemonPrevEvolId,
                pp.pre_evolution_name AS pokemonPrevEvolName,
                pt.id_types_1 AS pokemonTypesFirstId,
                pt.id_types_2 AS pokemonTypesSecondId,
                t1.name AS pokemonTypesFirstName,
                t1.image AS pokemonTypesFirstImage,
                t1.english_name AS pokemonTypesFirstName_EN,
                t2.name AS pokemonTypesSecondName,
                t2.image AS pokemonTypesSecondImage,
                t2.english_name AS pokemonTypesSecondName_EN
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemon_stats` ps ON p.id_pokedex = ps.id_pokedex
                LEFT JOIN `dex_pokemon_types` pt ON p.id_pokedex = pt.id_pokedex
                LEFT JOIN `dex_pokemon_pre_evolutions` pp ON p.id_pokedex = pp.id_pokedex
                LEFT JOIN `dex_pokemon_evolutions` pe ON p.id_pokedex = pe.id_pokedex 
                LEFT JOIN `dex_types` t1 ON pt.id_types_1 = t1.id_type
                LEFT JOIN `dex_types` t2 ON pt.id_types_2 = t2.id_type
            WHERE 
                p.id_pokedex = ?
            GROUP BY 
                p.id_pokedex;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$pokedexID]);

        return $stmt->fetch();
    }

    public function getPokemonByName($name) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.id_pokedex AS pokemonId,
                p.name AS pokemonName,
                p.image AS pokemonImage,
                p.generation AS pokemonGeneration,
                ps.hp AS pokemonStatsHp,
                ps.attack AS pokemonStatsAttack,
                ps.defense AS pokemonStatsDefense,
                ps.special_attack AS pokemonStatsSpecialAttack,
                ps.special_defense AS pokemonStatsSpecialDefense,
                ps.speed AS pokemonStatsSpeed,
                pe.evolution_id_pokedex AS pokemonNextEvolId,
                pe.evolution_name AS pokemonNextEvolName,
                pp.pre_evolution_id_pokedex AS pokemonPrevEvolId,
                pp.pre_evolution_name AS pokemonPrevEvolName,
                pt.id_types_1 AS pokemonTypesFirstId,
                pt.id_types_2 AS pokemonTypesSecondId,
                t1.name AS pokemonTypesFirstName,
                t1.image AS pokemonTypesFirstImage,
                t1.english_name AS pokemonTypesFirstName_EN,
                t2.name AS pokemonTypesSecondName,
                t2.image AS pokemonTypesSecondImage,
                t2.english_name AS pokemonTypesSecondName_EN
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemon_stats` ps ON p.id_pokedex = ps.id_pokedex
                LEFT JOIN `dex_pokemon_types` pt ON p.id_pokedex = pt.id_pokedex
                LEFT JOIN `dex_pokemon_pre_evolutions` pp ON p.id_pokedex = pp.id_pokedex
                LEFT JOIN `dex_pokemon_evolutions` pe ON p.id_pokedex = pe.id_pokedex 
                LEFT JOIN `dex_types` t1 ON pt.id_types_1 = t1.id_type
                LEFT JOIN `dex_types` t2 ON pt.id_types_2 = t2.id_type
            WHERE 
                p.name = ?
            GROUP BY 
                p.id_pokedex;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$name]);

        return $stmt->fetch();
    }

    public function getPokemonGeneration($generation) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.id_pokedex AS pokemonId,
                p.name AS pokemonName,
                p.image AS pokemonImage,
                p.generation AS pokemonGeneration,
                ps.hp AS pokemonStatsHp,
                ps.attack AS pokemonStatsAttack,
                ps.defense AS pokemonStatsDefense,
                ps.special_attack AS pokemonStatsSpecialAttack,
                ps.special_defense AS pokemonStatsSpecialDefense,
                ps.speed AS pokemonStatsSpeed,
                pe.evolution_id_pokedex AS pokemonNextEvolId,
                pe.evolution_name AS pokemonNextEvolName,
                pp.pre_evolution_id_pokedex AS pokemonPrevEvolId,
                pp.pre_evolution_name AS pokemonPrevEvolName,
                pt.id_types_1 AS pokemonTypesFirstId,
                pt.id_types_2 AS pokemonTypesSecondId,
                t1.name AS pokemonTypesFirstName,
                t1.image AS pokemonTypesFirstImage,
                t1.english_name AS pokemonTypesFirstName_EN,
                t2.name AS pokemonTypesSecondName,
                t2.image AS pokemonTypesSecondImage,
                t2.english_name AS pokemonTypesSecondName_EN
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemon_stats` ps ON p.id_pokedex = ps.id_pokedex
                LEFT JOIN `dex_pokemon_types` pt ON p.id_pokedex = pt.id_pokedex
                LEFT JOIN `dex_pokemon_pre_evolutions` pp ON p.id_pokedex = pp.id_pokedex
                LEFT JOIN `dex_pokemon_evolutions` pe ON p.id_pokedex = pe.id_pokedex 
                LEFT JOIN `dex_types` t1 ON pt.id_types_1 = t1.id_type
                LEFT JOIN `dex_types` t2 ON pt.id_types_2 = t2.id_type
            WHERE 
                p.generation = ?
            GROUP BY 
                p.id_pokedex;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$generation]);

        return $stmt->fetch();
    }

    public function getTypeIdByName($name) {
        $pdo = $this->connexion();

        $query = "SELECT id_type from dex_types where name= ?";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$name]);

        if(!$stmt->fetch()) {
            $this->addTypesAll();
            $stmt->execute([$name]);
        }

        $reponse = $stmt->fetch();
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

                $query = "INSERT INTO `dex_types` (id_type, name, image, english_name) VALUES (?, ?, ?, ?)";

                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    $type['typeId'],
                    $type['typeName'],
                    $imagePath,
                    $type['typeEnglishName']
                ]);
            }

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function addPokemon($pokemonData) {
        $pdo = $this->connexion();

        try {
            $pdo->beginTransaction();

            $pokemonId = formatPokedexId($pokemonData['pokemonId']);

            // Télécharger l'image du Pokémon
            $imagePath = downloadPokemonImage($pokemonData['pokemonImage'], $pokemonData['pokemonId'], $pokemonData['pokemonName']);

            // Insertion des informations générales du Pokémon
            $query = "
                INSERT INTO `dex_pokemons` (id_pokedex, name, image, generation) 
                VALUES (?, ?, ?, ?)
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                $pokemonData['pokemonName'],
                $imagePath,
                $pokemonData['pokemonGeneration']
            ]);

            // Insertion des statistiques
            $query = "
                INSERT INTO `dex_pokemon_stats` (id_pokedex, hp, attack, defense, special_attack, special_defense, speed) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
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

            // Insertion de la prochaine evolution
            $query = "
                INSERT INTO `dex_pokemon_evolutions` (id_pokedex, evolution_id_pokedex, evolution_name) 
                VALUES (?, ?, ?)
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                formatPokedexId($pokemonData['pokemonNextEvolId']),
                $pokemonData['pokemonNextEvolName']
            ]);

            // Insertion de la precedente evolution
            $query = "
                INSERT INTO `dex_pokemon_pre_evolutions` (id_pokedex, pre_evolution_id_pokedex, pre_evolution_name) 
                VALUES (?, ?, ?)
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                formatPokedexId($pokemonData['pokemonPrevEvolId']),
                $pokemonData['pokemonPrevEvolName']
            ]);

            // Insertion des types
            $query = "
                INSERT INTO `dex_pokemon_types` (id_pokedex, id_types_1, id_types_2) 
                VALUES (?, ?, ?)
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
        return "
            <div class='pokemon__card {$pokemon['type1_name']}'>
                <img src='{$pokemon['image']}' role='img' alt='{$pokemon['name']}' title='{$pokemon['name']}' aria-label='{$pokemon['name']}' loading='lazy' width='52' height='52'/>
                <h2>#{$pokemon['id_pokedex']} {$pokemon['name']}</h2>
            </div>
        ";
    }

    public function UIPokemon($pokemon) {
        return "
            <div class='pokemon'>
               <div class='id-background'>
                    <span class='id'>#{$pokemon['id_pokedex']}</span>
               </div>
               <div class='pokemon__identity'>
                <img src='{$pokemon['image']}' role='img' alt='{$pokemon['name']}' title='{$pokemon['name']}' aria-label='{$pokemon['name']}' loading='lazy' width='52' height='52'/>
                <div class='pokemon-informations'>
                    <h2>{$pokemon['name']}</h2>
                    <p>ID: {$pokemon['id_pokedex']}</p>
                </div>
               </div>
               <div class='pokemon__evolution'>
                    <img src='1' role='img' alt='{$pokemon['name']}' title='{$pokemon['name']}' aria-label='{$pokemon['name']}' loading='lazy' width='52' height='52'/>
                    <h3>{$pokemon['evolution_name']}</h3>
                </div>
                <div class='pokemon__pre-evolution'>
                    <img src='1' role='img' alt='{$pokemon['name']}' title='{$pokemon['name']}' aria-label='{$pokemon['name']}' loading='lazy' width='52' height='52'/>
                    <h3>{$pokemon['pre_evolution_name']}</h3>
                </div>
                <div class='pokemon__stats-title'>
                    <h3>Stats</h3>
                </div>
                <div class='pokemon__stats'>
                    <div class='pokemon__pre-evolution__items'>
                        <h4>HP</h4>
                        <progress max='255' value='{$pokemon['hp']}'></progress>
                        <p>{$pokemon['hp']}</p>
                    </div>
                    <div class='pokemon__pre-evolution__items'>
                        <h4>Attack</h4>
                        <progress max='255' value='{$pokemon['attack']}'></progress>
                        <p>{$pokemon['attack']}</p>
                    </div>
                    <div class='pokemon__pre-evolution__items'>
                        <h4>Defense</h4>
                        <progress max='255' value='{$pokemon['defense']}'></progress>
                        <p>{$pokemon['defense']}</p>
                    </div>
                    <div class='pokemon__pre-evolution__items'>
                        <h4>Special Attack</h4>
                        <progress max='255' value='{$pokemon['special_attack']}'></progress>
                        <p>{$pokemon['special_attack']}</p>
                    </div>
                    <div class='pokemon__pre-evolution__items'>
                        <h4>Special Defense	</h4>
                        <progress max='255' value='{$pokemon['special_defense']}'></progress>
                        <p>{$pokemon['special_defense']}</p>
                    </div>
                    <div class='pokemon__pre-evolution__items'>
                        <h4>Speed</h4>
                        <progress max='255' value='{$pokemon['speed']}'></progress>
                        <p>{$pokemon['speed']}</p>
                    </div>
                </div>
            </div>
        ";
    }
}

?>