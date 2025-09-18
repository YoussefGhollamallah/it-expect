<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1) Charger l’autoload Composer (chemins relatifs possibles)
$autoloads = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php'
];
foreach ($autoloads as $file) {
    if (file_exists($file)) {
        require $file;
        break;
    }
}

// 2) Définir quelques valeurs d’environnement par défaut
$defaults = [
    'APP_ENV'  => 'test',
];
foreach ($defaults as $k => $v) {
    if (getenv($k) === false || getenv($k) === '') {
        putenv("$k=$v");
    }
}

// 3) Helper global PDO pour la DB de test (MySQL), avec création auto du schéma
function cinetech_test_pdo(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $db   = getenv('DB_NAME') ?: 'cinetech_test';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';

    // 3.1 Connexion au serveur MySQL (sans db) avec fallback root/no pass si besoin
    $dsnServer = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $host, $port);

    try {
        $serverPdo = new PDO($dsnServer, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (Throwable $e) {
        // Fallback courant WAMP/XAMPP
        $userFallback = 'root';
        $passFallback = '';
        $serverPdo = new PDO($dsnServer, $userFallback, $passFallback, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    // 3.2 Créer la base si nécessaire puis se connecter à la base
    $serverPdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $dsnDb = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $db);
    $pdo = new PDO($dsnDb, $serverPdo->getAttribute(PDO::ATTR_CONNECTION_STATUS) ? ($user ?: 'root') : $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // 3.3 Réinitialiser complètement le schéma pour garantir l'isolation des tests
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
    $pdo->exec('DROP TABLE IF EXISTS favoris');
    $pdo->exec('DROP TABLE IF EXISTS commentaire');
    $pdo->exec('DROP TABLE IF EXISTS user');
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

    // Recréer les tables sans contrainte UNIQUE sur email (pour éviter les collisions entre tests setUp)
    $pdo->exec('CREATE TABLE user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(100),
        lastname VARCHAR(100),
        email VARCHAR(190) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE favoris (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        element_id INT NOT NULL,
        element_type ENUM("film","serie") NOT NULL,
        title VARCHAR(255) NOT NULL,
        poster_path VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_user_element (user_id, element_id, element_type),
        INDEX idx_user (user_id),
        CONSTRAINT fk_favoris_user FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE commentaire (
        id_commentaire INT AUTO_INCREMENT PRIMARY KEY,
        content TEXT NOT NULL,
        id_user INT NOT NULL,
        id_media INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_comment_user (id_user),
        INDEX idx_comment_media (id_media),
        CONSTRAINT fk_comment_user FOREIGN KEY (id_user) REFERENCES user(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    // 3.4 Seed utilisateurs requis pour les tests dépendants (ids 1,2,9)
    $pdo->exec("INSERT INTO user (id, firstname, lastname, email, password) VALUES (1, 'Seed', 'User', 'seed1@example.com', 'dummy')");
    $pdo->exec("INSERT INTO user (id, firstname, lastname, email, password) VALUES (2, 'Seed', 'User', 'seed2@example.com', 'dummy')");
    $pdo->exec("INSERT INTO user (id, firstname, lastname, email, password) VALUES (9, 'Seed', 'User', 'seed9@example.com', 'dummy')");

    return $pdo;
}

// 4) Charger le double de Connexion pour forcer l’utilisation du même PDO de test
$testDouble = __DIR__ . '/doubles/config/Connexion.php';
if (file_exists($testDouble)) {
    require_once $testDouble;
}

