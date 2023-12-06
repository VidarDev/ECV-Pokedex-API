<?php
include __DIR__ . '/config.php';

class DAO {

    public function __construct() {

    }

    public function connexion() {
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

    public function getPokemon() {
        $bd = $this->connexion();
    }

    public function postPokemon() {
        $bd = $this->connexion();
    }

    public function putPokemon() {
        $bd = $this->connexion();
    }

    public function deletePokemon() {
        $bd = $this->connexion();
    }
}

?>