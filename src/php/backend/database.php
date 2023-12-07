<?php
include __DIR__ . '/config.php';
include __DIR__ . '/api.php';

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

    public function downloadPokemonImage($imageUrl, $pokedexId, $name) {
        $imagePath = "./img/pokemons/{$pokedexId}_{$name}.png";

        // Utilisez file_get_contents et file_put_contents pour télécharger et sauvegarder l'image
        $imageData = file_get_contents($imageUrl);
        if ($imageData !== false && !file_exists($imagePath)) {
            file_put_contents($imagePath, $imageData);
        }

        return $imageData !== false ? $imagePath : null;
    }

    public function downloadTypeImage($imageUrl, $name) {
        $imagePath = "./img/types/{$name}.png";

        // Utilisez file_get_contents et file_put_contents pour télécharger et sauvegarder l'image
        $imageData = file_get_contents($imageUrl);
        if ($imageData !== false && !file_exists($imagePath)) {
            file_put_contents($imagePath, $imageData);
        }

        return $imageData !== false ? $imagePath : null;
    }


    public function getPokemon($input) {
        $filterInput = ucfirst(strtolower(str_replace(' ', '', $input)));

        // Déterminer si l'entrée est un ID ou un nom
        $isId = is_numeric($filterInput);

        // Essayer de récupérer le Pokémon de la base de données
        $pokemon = $isId ? $this->getPokemonByPokedexID($input) : $this->getPokemonByName($input);

        // Si le Pokémon n'est pas trouvé en base de données, le récupérer via l'API
        if (!$pokemon) {
            $pokemonData = $isId ? $this->api->getPokemonByPokedexID($input) : $this->api->getPokemonByName($input);
            if ($pokemonData) {
                $this->addPokemon($pokemonData);
                $pokemon = $isId ? $this->getPokemonByPokedexID($input) : $this->getPokemonByName($input);
            }
        }

        return $pokemon;
    }

    public function getPokemonList() {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.id_pokedex,
                p.name,
                p.image,
                p.generation,
                ps.hp,
                ps.attack,
                ps.defense,
                ps.special_attack,
                ps.special_defense,
                ps.speed,
                pe.evolution_id_pokedex,
                pe.evolution_name,
                pp.pre_evolution_id_pokedex,
                pp.pre_evolution_name,
                pt.id_types_1,
                pt.id_types_2,
                t1.name AS type1_name,
                t1.image AS type1_image,
                t2.name AS type2_name,
                t2.image AS type2_image
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemon_stats` ps ON p.id_pokedex = ps.id_pokedex
                LEFT JOIN `dex_pokemon_types` pt ON p.id_pokedex = pt.id_pokedex
                LEFT JOIN `dex_pokemon_pre_evolutions` pp ON p.id_pokedex = pp.id_pokedex
                LEFT JOIN `dex_pokemon_evolutions` pe ON p.id_pokedex = pe.id_pokedex 
                LEFT JOIN `dex_types` t1 ON pt.id_types_1 = t1.id
                LEFT JOIN `dex_types` t2 ON pt.id_types_2 = t2.id
            GROUP BY 
                p.id_pokedex;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPokemonByPokedexID($pokedexID) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.id_pokedex,
                p.name,
                p.image,
                p.generation,
                ps.hp,
                ps.attack,
                ps.defense,
                ps.special_attack,
                ps.special_defense,
                ps.speed,
                pe.evolution_id_pokedex,
                pe.evolution_name,
                pp.pre_evolution_id_pokedex,
                pp.pre_evolution_name,
                pt.id_types_1,
                pt.id_types_2,
                t1.name AS type1_name,
                t1.image AS type1_image,
                t2.name AS type2_name,
                t2.image AS type2_image
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemon_stats` ps ON p.id_pokedex = ps.id_pokedex
                LEFT JOIN `dex_pokemon_types` pt ON p.id_pokedex = pt.id_pokedex
                LEFT JOIN `dex_pokemon_pre_evolutions` pp ON p.id_pokedex = pp.id_pokedex
                LEFT JOIN `dex_pokemon_evolutions` pe ON p.id_pokedex = pe.id_pokedex 
                LEFT JOIN `dex_types` t1 ON pt.id_types_1 = t1.id
                LEFT JOIN `dex_types` t2 ON pt.id_types_2 = t2.id
            WHERE 
                p.id_pokedex = ?
            GROUP BY 
                p.id_pokedex;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$pokedexID]);

        return $stmt->fetchAll();
    }

    public function getPokemonByName($name) {
        $pdo = $this->connexion();

        $query = "
           SELECT 
                p.id_pokedex,
                p.name,
                p.image,
                p.generation,
                ps.hp,
                ps.attack,
                ps.defense,
                ps.special_attack,
                ps.special_defense,
                ps.speed,
                pe.evolution_id_pokedex,
                pe.evolution_name,
                pp.pre_evolution_id_pokedex,
                pp.pre_evolution_name,
                pt.id_types_1,
                pt.id_types_2,
                t1.name AS type1_name,
                t1.image AS type1_image,
                t2.name AS type2_name,
                t2.image AS type2_image
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemon_stats` ps ON p.id_pokedex = ps.id_pokedex
                LEFT JOIN `dex_pokemon_types` pt ON p.id_pokedex = pt.id_pokedex
                LEFT JOIN `dex_pokemon_pre_evolutions` pp ON p.id_pokedex = pp.id_pokedex
                LEFT JOIN `dex_pokemon_evolutions` pe ON p.id_pokedex = pe.id_pokedex 
                LEFT JOIN `dex_types` t1 ON pt.id_types_1 = t1.id
                LEFT JOIN `dex_types` t2 ON pt.id_types_2 = t2.id
            WHERE 
                p.name = ?
            GROUP BY 
                p.id_pokedex;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$name]);

        return $stmt->fetchAll();
    }

    public function getPokemonByGeneration($generation) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.id_pokedex,
                p.name,
                p.image,
                p.generation,
                ps.hp,
                ps.attack,
                ps.defense,
                ps.special_attack,
                ps.special_defense,
                ps.speed,
                pe.evolution_id_pokedex,
                pe.evolution_name,
                pp.pre_evolution_id_pokedex,
                pp.pre_evolution_name,
                pt.id_types_1,
                pt.id_types_2,
                t1.name AS type1_name,
                t1.image AS type1_image,
                t2.name AS type2_name,
                t2.image AS type2_image
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemon_stats` ps ON p.id_pokedex = ps.id_pokedex
                LEFT JOIN `dex_pokemon_types` pt ON p.id_pokedex = pt.id_pokedex
                LEFT JOIN `dex_pokemon_pre_evolutions` pp ON p.id_pokedex = pp.id_pokedex
                LEFT JOIN `dex_pokemon_evolutions` pe ON p.id_pokedex = pe.id_pokedex 
                LEFT JOIN `dex_types` t1 ON pt.id_types_1 = t1.id
                LEFT JOIN `dex_types` t2 ON pt.id_types_2 = t2.id
            WHERE 
                p.generation = ?
            GROUP BY 
                p.id_pokedex;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$generation]);

        return $stmt->fetchAll();
    }

    public function getTypeByName($name) {
        $pdo = $this->connexion();

        $query = "SELECT id from dex_types where name= ?";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$name]);

        if(!$stmt->fetch()) {
            $this->addTypes();
            $stmt->execute([$name]);
        }

        return $stmt->fetch();
    }

    public function addTypes() {
        $pdo = $this->connexion();

        try {
            $pdo->beginTransaction();

            $types = $this->api->getTypes();

            foreach($types as $type) {
                $image = $type['image'];
                $name = $type['name'];

                $imagePath = $this->downloadTypeImage($image, $name);

                $query = "INSERT INTO `dex_types` (name, image) VALUES (?, ?)";

                $stmt = $pdo->prepare($query);
                $stmt->execute([$name, $imagePath]);
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

            $pokemonId = $pokemonData['id_pokedex'];

            // Télécharger l'image du Pokémon
            $imagePath = $this->downloadPokemonImage($pokemonData['image'], $pokemonData['id_pokedex'], $pokemonData['name']);

            // Insertion des informations générales du Pokémon
            $query = "
                INSERT INTO `dex_pokemons` (id_pokedex, name, image, generation) 
                VALUES (?, ?, ?, ?)
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                $pokemonData['name'],
                $imagePath,
                $pokemonData['generation']
            ]);

            // Insertion des statistiques
            $query = "
                INSERT INTO `dex_pokemon_stats` (id_pokedex, hp, attack, defense, special_attack, special_defense, speed) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                $pokemonData['stats']['HP'],
                $pokemonData['stats']['attack'],
                $pokemonData['stats']['defense'],
                $pokemonData['stats']['special_attack'],
                $pokemonData['stats']['special_defense'],
                $pokemonData['stats']['speed']
            ]);

            // Insertion des statistiques
            $query = "
                INSERT INTO `dex_pokemon_evolutions` (id_pokedex, evolution_id_pokedex, evolution_name) 
                VALUES (?, ?, ?)
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                $pokemonData['evolution_id_pokedex'],
                $pokemonData['evolution_name']
            ]);

            // Insertion des statistiques
            $query = "
                INSERT INTO `dex_pokemon_pre_evolutions` (id_pokedex, pre_evolution_id_pokedex, pre_evolution_name) 
                VALUES (?, ?, ?)
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                $pokemonData['pre_evolution_id_pokedex'],
                $pokemonData['pre_evolution_name']
            ]);

            // Insertion des types
            $query = "
                INSERT INTO `dex_pokemon_types` (id_pokedex, id_types_1, id_types_2) 
                VALUES (?, ?, ?)
            ";

            if($pokemonData['id_types_name_1'] !== null) {
                $typeId = $this->getTypeByName($pokemonData['id_types_name_1']);
                var_dump($typeId['id']);

                if($pokemonData['id_types_name_2'] !== null) {
                    $typeId2 = $this->getTypeByName($pokemonData['id_types_name_2']);
                } else {
                    $typeId2 = null;
                }

                $stmt = $pdo->prepare($query);
                $stmt->execute([$pokemonId, $typeId['id'], ($typeId2['id'])]);
            }

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

//    public function updateType($name) {
//        $pdo = $this->connexion();
//
//        $query = "";
//
//        $stmt = $pdo->prepare($query);
//        $stmt->execute([]);
//    }
//
//    public function updatePokemon() {
//        $pdo = $this->connexion();
//
//        $query = "";
//
//        $stmt = $pdo->prepare($query);
//        $stmt->execute([]);
//    }
//
//    public function deletePokemon() {
//        $pdo = $this->connexion();
//
//        $query = "";
//
//        $stmt = $pdo->prepare($query);
//        $stmt->execute([]);
//    }

    public function UIPokemonCard($pokemon) {
        return "
            <div class='pokemon-card'>
                <img src='{$pokemon[0][2]}' alt='{$pokemon[0][1]}' />
                <p>0: {$pokemon[0][0]}</p>
                <p>1: {$pokemon[0][1]}</p>
                <p>2: {$pokemon[0][2]}</p>
                <p>3: {$pokemon[0][3]}</p>
                <p>4: {$pokemon[0][4]}</p>
                <p>5: {$pokemon[0][5]}</p>
                <p>6: {$pokemon[0][6]}</p>
                <p>7: {$pokemon[0][7]}</p>
                <p>8: {$pokemon[0][8]}</p>
                <p>9: {$pokemon[0][9]}</p>
                <p>10: {$pokemon[0][10]}</p>
                <p>11: {$pokemon[0][11]}</p>
                <p>12: {$pokemon[0][12]}</p>
                <p>13: {$pokemon[0][13]}</p>
                <p>14: {$pokemon[0][14]}</p>
                <p>15: {$pokemon[0][15]}</p>
            </div>
        ";
    }

    public function UIPokemon($pokemon) {
        return "
            <div class='pokemon-card'>
                <img src='{$pokemon['image']}' alt='{$pokemon['name']}' />
                <h2>{$pokemon['name']}</h2>
                <p>ID: {$pokemon['pokedexId']}</p>
            </div>
        ";
    }
}

?>