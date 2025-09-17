<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Controllers\commentaireController;
use App\Controllers\FavorisController;

$commentaire = new commentaireController();
$id_user = $_SESSION['user']['id_user'] ?? null;
$id_media = $media['id'] ?? null;

// Ajout d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'], $_POST['id_media'])) {
    if ($id_user) {
        $content = htmlspecialchars($_POST['content']);
        try {
            $message = $commentaire->addComment($content, $id_user, $id_media);
            echo "<p style='color: green;'>$message</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Vous devez être connecté pour ajouter un commentaire.</p>";
    }
}

// Suppression d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    if ($id_user) {
        $id_commentaire = (int)$_POST['comment_id'];
        $comment = $commentaire->getCommentById($id_commentaire);
        if ($comment && $comment['id_user'] === $id_user) {
            try {
                $message = $commentaire->deleteCommentByUser($id_commentaire, $id_user);
                echo "<p style='color: green;'>$message</p>";
            } catch (Exception $e) {
                echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p style='color: red;'>Vous ne pouvez supprimer que vos propres commentaires.</p>";
        }
    } else {
        echo "<p style='color: red;'>Vous devez être connecté pour supprimer un commentaire.</p>";
    }
}

$comments = $commentaire->getComments($id_media);

// Tableaux des jours et mois en français
$jours = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
$mois = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_favori'])) {
    $id_user = $_SESSION['user']['id_user'] ?? null;
    $id_media = $_POST['id_media'] ?? null;
    $title = $_POST['title'] ?? null;
    $poster_path = $_POST['poster_path'] ?? null;
    $type_media = $_POST['type_media'] ?? null;

    if ($id_user && $id_media && $title && $type_media) {
        // Appel du contrôleur pour ajouter un favori
        $favorisController = new FavorisController();
        $response = $favorisController->addFavori($id_user, $id_media, $type_media, $title, $poster_path);
        
        if ($response['success']) {
            echo "<p style='color: green;'>Votre favori a été ajouté avec succès !</p>";
        } else {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($response['message']) . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Tous les champs sont requis.</p>";
    }
}
?>


<section class="details">
    <div id="card-detail">
        <div id="img-detail">
            <img src="https://image.tmdb.org/t/p/w300/<?= htmlspecialchars($media['poster_path'] ?? '') ?>" alt="<?= htmlspecialchars($media['title'] ?? $media['name'] ?? 'Titre inconnu') ?>">
        </div>
        <div id="detail-info">
            <h2><?= htmlspecialchars($media['title'] ?? $media['name'] ?? 'Titre inconnu') ?></h2>
            <p><?= htmlspecialchars($media['overview'] ?? 'Aucune description disponible') ?></p>
            <p><?= isset($media['title']) ? 'Film' : 'Série' ?></p>

            <?php if (isset($media['release_date'])): ?>
                <p><strong>Date de sortie :</strong> <?= htmlspecialchars($media['release_date'] ?? 'Date inconnue') ?></p>
            <?php elseif (isset($media['first_air_date'])): ?>
                <p><strong>Date de première diffusion :</strong> <?= htmlspecialchars($media['first_air_date'] ?? 'Date inconnue') ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="actors">
        <h3>Acteurs principaux :</h3>
        <?php if (!empty($media['cast'])): ?>
            <div class="carousel">
                <?php foreach ($media['cast'] as $actor): ?>
                    <?php if (isset($actor['profile_path']) && $actor['profile_path']): ?>
                        <div class="slide">
                            <img src="https://image.tmdb.org/t/p/w92/<?= htmlspecialchars($actor['profile_path'] ?? '') ?>" alt="<?= htmlspecialchars($actor['name'] ?? 'Acteur inconnu') ?>">
                            <p><?= htmlspecialchars($actor['name'] ?? 'Acteur inconnu') ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun acteur disponible.</p>
        <?php endif; ?>
    </div>

    <div class="directors">
        <h3>Réalisateur :</h3>
        <?php if (isset($media['crew'])): ?>
            <ul>
                <?php foreach ($media['crew'] as $crew): ?>
                    <?php if ($crew['job'] === 'Director' && isset($crew['profile_path']) && $crew['profile_path']): ?>
                        <li>
                            <img src="https://image.tmdb.org/t/p/w92/<?= htmlspecialchars($crew['profile_path'] ?? '') ?>" alt="<?= htmlspecialchars($crew['name'] ?? 'Réalisateur inconnu') ?>">
                            <p><?= htmlspecialchars($crew['name'] ?? 'Réalisateur inconnu') ?></p>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun réalisateur disponible.</p>
        <?php endif; ?>
    </div>

    <h3>Commentaires</h3>
    <section id="comments">
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <?php 
                    $date = new DateTime($comment['created_at']);
                    $jourSemaine = $jours[$date->format('w')];
                    $moisFrancais = $mois[$date->format('n') - 1];
                    $formattedDate = ucfirst($jourSemaine) . ' ' . $date->format('d') . ' ' . $moisFrancais . ' ' . $date->format('Y'). " à " . $date->format('H:i:s');
                ?>
                <div class="comment">
                    <p><?= "Message de  <strong> " . ucfirst(htmlspecialchars($comment["firstname"])) ." " . strtoupper(htmlspecialchars($comment["lastname"])) . " </strong> : " . htmlspecialchars($comment['content']) ?></p>
                    <p>Posté le <?= htmlspecialchars($formattedDate) ?></p>
                    <?php if ($id_user === $comment['id_user']): ?>
                        <form action="" method="POST">
                            <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['id_commentaire']) ?>">
                            <button class="deletBtn" type="submit">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </div>
                <br>
                <hr>
                <br>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun commentaire pour l'instant.</p>
        <?php endif; ?>
    </section>

    <br>
    <!-- Formulaire pour ajouter aux favoris -->
<?php if (isset($_SESSION['user']) && isset($_SESSION['user']['id_user'])): ?>
    <form action="" method="POST">
        <input type="hidden" name="add_favori" value="1">
        <input type="hidden" name="id_media" value="<?= $id_media ?>">
        <input type="hidden" name="title" value="<?= htmlspecialchars($media['title'] ?? $media['name'] ?? 'Titre inconnu') ?>">
        <input type="hidden" name="poster_path" value="<?= htmlspecialchars($media['poster_path'] ?? '') ?>">
        <input type="hidden" name="type_media" value="<?= isset($media['title']) ? 'movie' : 'tv' ?>">
        <button type="submit">Ajouter aux favoris</button>
    </form>
<?php else: ?>
    <p>Veuillez <a href="<?= HOST ?>login">vous connecter</a> pour ajouter aux favoris.</p>
<?php endif; ?>


    <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['id_user'])): ?>
        <section id="add-comment">
            <h3>Laisser un commentaire</h3>
            <form action="" method="POST">
                <textarea name="content" rows="5" placeholder="Votre commentaire..." required></textarea>
                <input type="hidden" name="id_media" value="<?= htmlspecialchars($media['id'] ?? '') ?>">
                <button type="submit">Envoyer</button>
            </form>
        </section>
    <?php else: ?>
        <p>Veuillez <a href="<?= HOST ?>login">vous connecter</a> pour ajouter un commentaire.</p>
    <?php endif; ?>
</section>

<script>
    const carousel = document.querySelector('.carousel');
    if (carousel) {
        carousel.innerHTML += carousel.innerHTML;
    }
</script>
