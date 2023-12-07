-- Création de la base de données
CREATE DATABASE IF NOT EXISTS pokedex;
USE pokedex;

-- Table dex_pokemons
CREATE TABLE dex_pokemons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pokedex INT,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    generation INT
);

-- Table dex_types
CREATE TABLE dex_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255)
);

-- Table dex_pokemon_stats
CREATE TABLE dex_pokemon_stats (
    id_pokedex INT,
    hp INT,
    attack INT,
    defense INT,
    special_attack INT,
    special_defense INT,
    speed INT,
    FOREIGN KEY (id_pokedex) REFERENCES dex_pokemons(id_pokedex)
);

-- Table dex_pokemon_types
CREATE TABLE dex_pokemon_types (
    id_pokedex INT,
    id_types_1 INT,
    id_types_2 INT,
    FOREIGN KEY (id_pokedex) REFERENCES dex_pokemons(id_pokedex),
    FOREIGN KEY (id_types_1) REFERENCES dex_types(id),
    FOREIGN KEY (id_types_2) REFERENCES dex_types(id)
);

-- Table dex_pokemon_evolutions
CREATE TABLE dex_pokemon_evolutions (
    id_pokedex INT,
    evolution_id_pokedex INT,
    evolution_name VARCHAR(255),
    FOREIGN KEY (id_pokedex) REFERENCES dex_pokemons(id_pokedex)
);

-- Table dex_pokemon_pre_evolutions
CREATE TABLE dex_pokemon_pre_evolutions (
    id_pokedex INT,
    pre_evolution_id_pokedex INT,
    pre_evolution_name VARCHAR(255),
    FOREIGN KEY (id_pokedex) REFERENCES dex_pokemons(id_pokedex)
);


