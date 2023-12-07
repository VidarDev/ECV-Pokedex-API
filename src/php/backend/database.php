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

    public function getPokemonByGeneration($generation) {
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

        return $stmt->fetchAll();
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
        $formatPokedexId = formatPokedexId($pokemon['pokemonId']);

        return "
            <div class='pokemon-card {$pokemon['pokemonTypesFirstName_EN']}'>
                <div class='pokemon-card-top'>
                    <span class='pokemon-card-top__name'>{$pokemon['pokemonName']}</span>
                    <span class='pokemon-card-top__pokedex'>#{$formatPokedexId}</span>
                </div>
                <div class='pokemon-card-bottom'>
                    <div class='pokemon-card-bottom__types'>
                        <img src='{$pokemon['pokemonTypesFirstImage']}' role='img' alt='{$pokemon['pokemonTypesFirstName']}' title='{$pokemon['pokemonTypesFirstName']}' aria-label='{$pokemon['pokemonTypesFirstName']}' loading='lazy' width='200' height='200'/>
                        <img src='{$pokemon['pokemonTypesSecondImage']}' role='img' alt='{$pokemon['pokemonTypesSecondName']}' title='{$pokemon['pokemonTypesSecondName']}' aria-label='{$pokemon['pokemonTypesSecondName']}' loading='lazy' width='200' height='200'/>
                    </div>
                    <img class='pokemon-card-bottom__image' src='{$pokemon['pokemonImage']}' role='img' alt='{$pokemon['pokemonName']}' title='{$pokemon['pokemonName']}' aria-label='{$pokemon['pokemonName']}' loading='lazy' width='200' height='200'/>
                </div>
            </div>
        ";
    }

    public function UIPokemon($pokemon) {
        $formatPokedexId = formatPokedexId($pokemon['pokemonId']);

        return "
            <div class='pokemon {$pokemon['pokemonTypesFirstName_EN']}'>
               <span class='pokemon__pokedex-id'>#{$formatPokedexId}</span>
               <div class='pokemon-top'>
                  <div class='pokemon-top__identity'>
                    <div class='pokemon-types'>
                        <span>
                            <img src='{$pokemon['pokemonTypesFirstImage']}' role='img' alt='{$pokemon['pokemonTypesFirstName']}' title='{$pokemon['pokemonTypesFirstName']}' aria-label='{$pokemon['pokemonTypesFirstName']}' loading='lazy' width='200' height='200'/>
                            {$pokemon['pokemonTypesFirstName']}
                        </span>
                        <span>
                            <img src='{$pokemon['pokemonTypesSecondImage']}' role='img' alt='{$pokemon['pokemonTypesSecondName']}' title='{$pokemon['pokemonTypesSecondName']}' aria-label='{$pokemon['pokemonTypesSecondName']}' loading='lazy' width='200' height='200'/>
                            {$pokemon['pokemonTypesSecondName']}
                        </span>
                    </div>
                    <h1 class='pokemon-name'>{$pokemon['pokemonName']}</h1>
                  </div>
                  <div class='pokemon-top__images'>
                     <div class='pokemon-current'>
                        <img src='{$pokemon['pokemonImage']}' role='img' alt='{$pokemon['pokemonName']}' title='{$pokemon['pokemonName']}' aria-label='{$pokemon['pokemonName']}' loading='lazy' width='200' height='200'/>
                     </div>
                     <div class='pokemon-evolutions'>
                        <h2 class='screen-reader-only'>Evolutions</h2>
                        <div class='pokemon-evolutions__next'>
                            <img src='{$pokemon['pokemonImage']}' role='img' alt='{$pokemon['pokemonNextEvolName']}' title='{$pokemon['pokemonNextEvolName']}' aria-label='{$pokemon['pokemonNextEvolName']}' loading='lazy' width='200' height='200'/>
                            <h3 class='pokemon-name'>{$pokemon['pokemonNextEvolName']}</h3>
                        </div>
                        <div class='pokemon-evolutions__prev'>
                            <img src='{$pokemon['pokemonImage']}' role='img' alt='{$pokemon['pokemonPrevEvolName']}' title='{$pokemon['pokemonPrevEvolName']}' aria-label='{$pokemon['pokemonPrevEvolName']}' loading='lazy' width='200' height='200'/>
                            <h3 class='pokemon-name'>{$pokemon['pokemonPrevEvolName']}</h3>
                        </div>
                     </div>
                  </div>
               </div>
               <div class='pokemon-bottom'>
                    <h2 class='pokemon-bottom__title'>Stats</h2>
                    <div class='pokemon-bottom__wrapper'>
                         <div class='stats-items'>
                            <h3 class='stats-items__label'>HP</h3>
                            <div class='stats-items__progress'>
                                <span class='indicator' style='width: calc(({$pokemon['pokemonStatsHp']} * 100%) / 255);'></span>
                            </div>
                            <span class='stats-items__value'>{$pokemon['pokemonStatsHp']}</span>
                         </div>
                         <div class='stats-items'>
                            <h3 class='stats-items__label'>Attack</h3>
                            <div class='stats-items__progress'>
                                <span class='indicator' style='width: calc(({$pokemon['pokemonStatsAttack']} * 100%) / 255);'></span>
                            </div>
                            <span class='stats-items__value'>{$pokemon['pokemonStatsAttack']}</span>
                         </div>
                         <div class='stats-items'>
                            <h3 class='stats-items__label'>Defense</h3>
                            <div class='stats-items__progress'>
                                <span class='indicator' style='width: calc(({$pokemon['pokemonStatsDefense']} * 100%) / 255);'></span>
                            </div>
                            <span class='stats-items__value'>{$pokemon['pokemonStatsDefense']}</span>
                         </div>
                         <div class='stats-items'>
                            <h3 class='stats-items__label'>Special Attack</h3>
                            <div class='stats-items__progress'>
                                <span class='indicator' style='width: calc(({$pokemon['pokemonStatsSpecialAttack']} * 100%) / 255);'></span>
                            </div>
                            <span class='stats-items__value'>{$pokemon['pokemonStatsSpecialAttack']}</span>
                         </div>
                         <div class='stats-items'>
                            <h3 class='stats-items__label'>Special Defense</h3>
                            <div class='stats-items__progress'>
                                <span class='indicator' style='width: calc(({$pokemon['pokemonStatsSpecialDefense']} * 100%) / 255);'></span>
                            </div>
                            <span class='stats-items__value'>{$pokemon['pokemonStatsSpecialDefense']}</span>
                         </div>
                         <div class='stats-items'>
                            <h3 class='stats-items__label'>Speed</h3>
                            <div class='stats-items__progress'>
                                <span class='indicator' style='width: calc(({$pokemon['pokemonStatsSpeed']} * 100%) / 255);'></span>
                            </div>
                            <span class='stats-items__value'>{$pokemon['pokemonStatsSpeed']}</span>
                         </div>
                    </div>
                    </div>
                </div>
           </div>
        ";
    }
}

?>