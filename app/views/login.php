<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Controllers\UserController;

$user = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $userdata = $user->login($_POST['username'], $_POST['password']);
        if ($userdata) {
            // Structure cohérente pour la session
            $_SESSION['user'] = [
                'id_user' => $userdata['id'], // Remplace par la clé réelle de l'ID utilisateur
                'firstname' => $userdata['firstname'],
                'lastname' => $userdata['lastname'],
                'email' => $userdata['email']
            ];
            header('Location: profile'); // Redirection après connexion
            exit();
        } else {
            $error_message = 'Email ou mot de passe incorrect.';
        }
    } else {
        $error_message = 'Veuillez remplir tous les champs.';
    }
}

// Redirection si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    header('Location: profile');
    exit();
}

?>

<section>
    <h1>Login</h1>
    <form action="" class="register" method="post">
        <div>
            <input type="text" name="username" placeholder="Email" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        </div>
        <div>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </form>

    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <p>Vous n'avez pas de compte ? <a href="register">Inscrivez-vous</a></p>
</section>
