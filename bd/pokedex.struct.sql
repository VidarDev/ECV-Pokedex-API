-- Création de la base de données
CREATE DATABASE IF NOT EXISTS pokedex;
USE pokedex;

-- Table dex_pokemons
CREATE TABLE dex_pokemons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pokedexId INT,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    apiGeneration INT
);

-- Table dex_types
CREATE TABLE dex_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255)
);

-- Table dex_pokemon_stats
CREATE TABLE dex_pokemon_stats (
    pokedexId INT,
    HP INT,
    attack INT,
    defense INT,
    special_attack INT,
    special_defense INT,
    speed INT,
    FOREIGN KEY (pokedexId) REFERENCES dex_pokemons(id)
);

-- Table dex_pokemon_types
CREATE TABLE dex_pokemon_types (
    pokedexId INT,
    typeId INT,
    FOREIGN KEY (pokedexId) REFERENCES dex_pokemons(id),
    FOREIGN KEY (typeId) REFERENCES dex_types(id)
);

-- Table dex_pokemon_evolutions
CREATE TABLE dex_pokemon_evolutions (
    pokedexId INT,
    evolution_pokedexId INT,
    evolution_name VARCHAR(255),
    FOREIGN KEY (pokedexId) REFERENCES dex_pokemons(id)
);

-- Table dex_pokemon_pre_evolutions
CREATE TABLE dex_pokemon_pre_evolutions (
    pokedexId INT,
    pre_evolution_pokedexId INT,
    pre_evolution_name VARCHAR(255),
    FOREIGN KEY (pokedexId) REFERENCES dex_pokemons(id)
);


