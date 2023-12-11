-- Création de la base de données
CREATE DATABASE IF NOT EXISTS pokedex;
USE pokedex;

-- Table dex_pokemons
CREATE TABLE dex_pokemons (
    id INT NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    generation INT,
    id_next_evolution INT,
    id_prev_evolution INT
);

-- Table dex_types
CREATE TABLE dex_types (
    id_type INT NOT NULL PRIMARY KEY,
    name_FR VARCHAR(255) NOT NULL,
    name_EN VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL
);

-- Table dex_pokemon_stats
CREATE TABLE dex_pokemons_stats (
    id_pokemon INT,
    hp INT,
    attack INT,
    defense INT,
    special_attack INT,
    special_defense INT,
    speed INT,
    FOREIGN KEY (id_pokemon) REFERENCES dex_pokemons(id)
);

-- Table dex_pokemon_types
CREATE TABLE dex_pokemons_types (
    id_pokemon INT NOT NULL,
    id_type_first INT NOT NULL,
    id_type_second INT,
    FOREIGN KEY (id_pokemon) REFERENCES dex_pokemons(id),
    FOREIGN KEY (id_type_first) REFERENCES dex_types(id_type),
    FOREIGN KEY (id_type_second) REFERENCES dex_types(id_type)
);


