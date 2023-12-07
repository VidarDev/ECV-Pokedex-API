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
                p.pokedexId,
                p.name,
                p.image,
                p.apiGeneration,
                ps.HP,
                ps.attack,
                ps.defense,
                ps.special_attack,
                ps.special_defense,
                ps.speed,
                pp.pre_evolution_pokedexId AS preEvolPokedexId,
                pp.pre_evolution_name AS preEvolName,
                pe.evolution_pokedexId AS evolPokedexId,
                pe.evolution_name AS evolName,
                GROUP_CONCAT(t.name SEPARATOR ',') AS typeNames,
                GROUP_CONCAT(t.image SEPARATOR ',') AS typeImages
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemon_stats` ps ON p.pokedexId = ps.pokedexId
                LEFT JOIN `dex_pokemon_types` pt ON p.pokedexId = pt.pokedexId
                LEFT JOIN `dex_pokemon_pre_evolutions` pp ON p.pokedexId = pp.pokedexId
                LEFT JOIN `dex_pokemon_evolutions` pe ON p.pokedexId = pe.pokedexId
                LEFT JOIN `dex_types` t ON pt.pokedexId = t.id
            GROUP BY 
                p.pokedexId;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPokemonByPokedexID($pokedexID) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.pokedexId,
                p.name,
                p.image,
                p.apiGeneration,
                ps.HP,
                ps.attack,
                ps.defense,
                ps.special_attack,
                ps.special_defense,
                ps.speed,
                pp.pre_evolution_pokedexId AS preEvolPokedexId,
                pp.pre_evolution_name AS preEvolName,
                pe.evolution_pokedexId AS evolPokedexId,
                pe.evolution_name AS evolName,
                GROUP_CONCAT(t.name SEPARATOR ',') AS typeNames,
                GROUP_CONCAT(t.image SEPARATOR ',') AS typeImages
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemon_stats` ps ON p.pokedexId = ps.pokedexId
                LEFT JOIN `dex_pokemon_types` pt ON p.pokedexId = pt.pokedexId
                LEFT JOIN `dex_pokemon_pre_evolutions` pp ON p.pokedexId = pp.pokedexId
                LEFT JOIN `dex_pokemon_evolutions` pe ON p.pokedexId = pe.pokedexId
                LEFT JOIN `dex_types` t ON pt.pokedexId = t.id
            WHERE 
                p.pokedexId = ?
            GROUP BY 
                p.pokedexId;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$pokedexID]);

        return $stmt->fetchAll();
    }

    public function getPokemonByName($name) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.pokedexId,
                p.name,
                p.image,
                p.apiGeneration,
                ps.HP,
                ps.attack,
                ps.defense,
                ps.special_attack,
                ps.special_defense,
                ps.speed,
                pp.pre_evolution_pokedexId AS preEvolPokedexId,
                pp.pre_evolution_name AS preEvolName,
                pe.evolution_pokedexId AS evolPokedexId,
                pe.evolution_name AS evolName,
                GROUP_CONCAT(t.name SEPARATOR ',') AS typeNames,
                GROUP_CONCAT(t.image SEPARATOR ',') AS typeImages
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemon_stats` ps ON p.pokedexId = ps.pokedexId
                LEFT JOIN `dex_pokemon_types` pt ON p.pokedexId = pt.pokedexId
                LEFT JOIN `dex_pokemon_pre_evolutions` pp ON p.pokedexId = pp.pokedexId
                LEFT JOIN `dex_pokemon_evolutions` pe ON p.pokedexId = pe.pokedexId
                LEFT JOIN `dex_types` t ON pt.pokedexId = t.id
            WHERE 
                p.name = ?
            GROUP BY 
                p.pokedexId;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$name]);

        return $stmt->fetchAll();
    }

    public function getPokemonByGeneration($generation) {
        $pdo = $this->connexion();

        $query = "
            SELECT 
                p.pokedexId,
                p.name,
                p.image,
                p.apiGeneration,
                ps.HP,
                ps.attack,
                ps.defense,
                ps.special_attack,
                ps.special_defense,
                ps.speed,
                pp.pre_evolution_pokedexId AS preEvolPokedexId,
                pp.pre_evolution_name AS preEvolName,
                pe.evolution_pokedexId AS evolPokedexId,
                pe.evolution_name AS evolName,
                GROUP_CONCAT(t.name SEPARATOR ',') AS typeNames,
                GROUP_CONCAT(t.image SEPARATOR ',') AS typeImages
            FROM 
                `dex_pokemons` p
                LEFT JOIN `dex_pokemon_stats` ps ON p.pokedexId = ps.pokedexId
                LEFT JOIN `dex_pokemon_types` pt ON p.pokedexId = pt.pokedexId
                LEFT JOIN `dex_pokemon_pre_evolutions` pp ON p.pokedexId = pp.pokedexId
                LEFT JOIN `dex_pokemon_evolutions` pe ON p.pokedexId = pe.pokedexId
                LEFT JOIN `dex_types` t ON pt.pokedexId = t.id
            WHERE 
                p.apiGeneration = ?
            GROUP BY 
                p.pokedexId;
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

            $pokemonId = $pokemonData['pokedexId'];

            // Télécharger l'image du Pokémon
            $imagePath = $this->downloadPokemonImage($pokemonData['image'], $pokemonData['pokedexId'], $pokemonData['name']);

            // Insertion des informations générales du Pokémon
            $query = "
                INSERT INTO `dex_pokemons` (pokedexId, name, image, apiGeneration) 
                VALUES (?, ?, ?, ?)
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                $pokemonData['name'],
                $imagePath,
                $pokemonData['apiGeneration']
            ]);

            // Insertion des statistiques
            $query = "
                INSERT INTO `dex_pokemon_stats` (pokedexId, HP, attack, defense, special_attack, special_defense, speed) 
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
                INSERT INTO `dex_pokemon_evolutions` (pokedexId, evolution_pokedexId, evolution_name) 
                VALUES (?, ?, ?)
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                $pokemonData['evolution_pokedexId'],
                $pokemonData['evolution_name']
            ]);

            // Insertion des statistiques
            $query = "
                INSERT INTO `dex_pokemon_pre_evolutions` (pokedexId, pre_evolution_pokedexId, pre_evolution_name) 
                VALUES (?, ?, ?)
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $pokemonId,
                $pokemonData['pre_evolution_pokedexId'],
                $pokemonData['pre_evolution_name']
            ]);

            // Insertion des types
            $query = "
                INSERT INTO `dex_pokemon_types` (pokedexId, typeId) 
                VALUES (?, ?)
            ";

            $stmt = $pdo->prepare($query);
            foreach ($pokemonData['apiTypes'] as $type) {
                $typeId = $this->getTypeByName($type['name'], $type['image']);
                $stmt->execute([$pokemonId, $typeId]);
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