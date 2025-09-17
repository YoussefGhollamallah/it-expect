<?php

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user'])) {
    header('Location: connexion');
    exit();
}

?>

<section id="profil">
    <h3>Mes informations</h3>
    <p>Prénom : <?php echo $_SESSION['user']['firstname'] ;?></p>
    <p>Nom : <?php echo $_SESSION['user']['lastname']; ?></p>
    <p>Email : <?php echo $_SESSION['user']['email'] ;?></p>
</section>
