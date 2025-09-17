<?php

require_once config . "api.php";
require_once config . "connexion.php";

// Vérifier si une action est passée
if (isset($_GET['action'])) {
    session_start();

    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
        exit;
    }

    $userId = $_SESSION['user']['id_user'];

    if ($_GET['action'] === 'add_favorite') {
        $filmId = $_GET['film_id'] ?? null;

        if (!$filmId) {
            echo json_encode(['success' => false, 'message' => 'ID du film manquant']);
            exit;
        }

        // Ajouter le film dans la table favoris
        try {
            $stmt = $pdo->prepare('INSERT INTO favoris (id_user, id_media) VALUES (:user_id, :film_id)');
            $stmt->execute(['user_id' => $userId, 'film_id' => $filmId]);
            echo json_encode(['success' => true, 'message' => 'Film ajouté aux favoris']);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Gérer les doublons
                echo json_encode(['success' => false, 'message' => 'Film déjà dans vos favoris']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout: ' . $e->getMessage()]);
            }
        }
        exit;
    }
}

// Si aucune action, continuer à afficher la page des films
$url = API_FILM_TENDANCE_URL;

?>

<section>
    <h1>Films Populaires</h1>
    <div id="film"></div>
</section>

<script src="<?= ASSETS; ?>js/film.js"></script>