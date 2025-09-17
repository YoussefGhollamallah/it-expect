<?php

use App\Controllers\UserController;

$user = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Vérifications des champs requis
    if (empty($_POST['firstname'])) {
        $errors['firstname'] = 'Le prénom est requis';
    }
    if (empty($_POST['lastname'])) {
        $errors['lastname'] = 'Le nom est requis';
    }
    if (empty($_POST['email'])) {
        $errors['email'] = 'L\'email est requis';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Le mot de passe est requis';
    } elseif (strlen($_POST['password']) < 8) {
        $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères';
    }

    // Vérification de la confirmation du mot de passe
    if ($_POST['password'] !== $_POST['password_confirm']) {
        $errors['password_confirm'] = 'Les mots de passe ne correspondent pas';
    }

    // Si des erreurs sont présentes, elles seront affichées
    if (empty($errors)) {
        // Préparation des données pour l'insertion
        $firstname = strtolower(htmlspecialchars($_POST['firstname']));
        $lastname = strtolower(htmlspecialchars($_POST['lastname']));
        $email = strtolower(htmlspecialchars($_POST['email']));
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Enregistrement de l'utilisateur
        $user->register($firstname, $lastname, $email, $password);

        // Redirection en cas de succès
        header('Location: login');
        exit;
    }
}
?>

<section>
    <h1>Register</h1>
    <form action="" class="register" method="post">
        <div>
            <?php if (isset($errors['firstname'])): ?><p><?= $errors['firstname'] ?></p> <?php endif; ?>
            <label for="firstname">Prénom</label>
            <input type="text" name="firstname" id="firstname" required>
        </div>
        <div>
            <?php if (isset($errors['lastname'])): ?><p><?= $errors['lastname'] ?></p> <?php endif; ?>
            <label for="lastname">Nom</label>
            <input type="text" name="lastname" id="lastname" required>
        </div>
        <div>
            <?php if (isset($errors['email'])): ?><p><?= $errors['email'] ?></p> <?php endif; ?>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            <?php if (isset($errors['password'])): ?><p><?= $errors['password'] ?></p> <?php endif; ?>
        </div>
        <div>
            <label for="password_confirm">Confirmer le mot de passe</label>
            <input type="password" name="password_confirm" id="password_confirm" required>
            <?php if (isset($errors['password_confirm'])): ?><p><?= $errors['password_confirm'] ?></p> <?php endif; ?>
        </div>
        <div>
            <input type="submit" value="Valider">
        </div>
    </form>

    <p>Vous avez déjà un compte ? <a href="login">Connectez-vous</a></p>
</section>
