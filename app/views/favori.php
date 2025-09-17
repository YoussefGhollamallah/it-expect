<?php
session_start();

use App\Controllers\FavorisController;

$favoris = [];

// Vérification de l'authentification
if (!isset($_SESSION['user']["id_user"])) {
    header("Location: " . BASE_URL . "login");
    exit;
}

$userId = (int) $_SESSION['user']["id_user"];

try {
    $favoriController = new FavorisController();

    // Suppression si une requête POST est envoyée
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_favori'])) {
        $idFavori = (int) $_POST['id_favori'];
        $favoriController->deleteFavori($idFavori, $userId);
        header("Location: favori " ); // Recharge la page pour voir les changements
        exit;
    }

    // Récupérer les favoris
    $favoris = $favoriController->getAllFavorisByID($userId);
} catch (Exception $e) {
    error_log("Erreur lors de la récupération ou suppression des favoris : " . $e->getMessage());
    $favoris = [];
}
?>
<section id="favori">
    <h1>Vos Favoris</h1>
    <?php if (!empty($favoris)): ?>
        <ul>
            <?php foreach ($favoris as $favori): ?>
                <li>
                    <a href="javascript:showDetail('<?php echo $favori['element_id']; ?>', '<?php echo $favori['element_type']; ?>')">
                        <div class="card-favori">
                            <?php if (!empty($favori['poster_patch'])): ?>
                                <img src="https://image.tmdb.org/t/p/w300/<?php echo htmlspecialchars($favori['poster_patch']); ?>" alt="Affiche de <?php echo htmlspecialchars($favori['title']); ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/92x138" alt="Affiche indisponible pour <?php echo htmlspecialchars($favori['title']); ?>">
                            <?php endif; ?>

                            <form method="post" style="display:inline;">
                                <input type="hidden" name="id_favori" value="<?php echo $favori['id_favori']; ?>">
                                <button type="submit" onclick="return confirm('Voulez-vous vraiment supprimer ce favori ?');">Supprimer</button>
                            </form>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Vous n'avez aucun favori.</p>
    <?php endif; ?>
</section>
<a href="<?php echo BASE_URL; ?>">Retour à l'accueil</a>

<script>
    function showDetail(id, type) {
        const baseUrl = "<?php echo BASE_URL; ?>";
        if (type === "movie") {
            window.location.href = `detail/${id}/film`;
        } else if (type === "tv") {
            window.location.href = `detail/${id}/serie`;
        } else {
            alert("Type de favori inconnu.");
        }
    }
</script>

</script>