<?php
// tests/doubles/config/Connexion.php
namespace config;

class Connexion {
    public $conn;

    public function connexionBDD() {
        // Use the shared SQLite memory connection created in tests/bootstrap.php
        $this->conn = \cinetech_test_pdo();
        return $this->conn;
    }
}
