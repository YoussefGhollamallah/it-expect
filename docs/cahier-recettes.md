# Cahier des Recettes - Projet Cinetech

1. Présentation du projet
    * **Nom du projet :** Cinetech
    * **Description :** Application web permettant de consulter les films/série (API TMDb), gérer des favoris et publier des commentaires
    * **Technologies :** PHP (MVC), MySQL, JavaScript, PHPUnit, Playwright.

2. Fonctionalités à tester
    1. Authentification & Utilisateurs.
        * Inscription d'un nouvel utilisateur (email unique, mot de passe haché).
        * Connexion (email + mot de passe corrects).
        * Déconnexion.
    2. Films & Séries
        * Affichage de la page d'accueil avec les tendances.
        * Consultation d'une fiche détaillée (film ou série).
    3. Favoris
        * Ajout d'un élément en favori.
        * Vérification d'un doublon (un même film/série ne pas être ajouté deux fois).
        * Suppression d'un favori.
    4. Commentaires
        * Ajout d'un commentaire lié un film/série.
        * Suppression d'un commentaire par son auteur.
        * Affichage des commentaires existants.
    5. Navigation & Routing
        * Page Accueil
        * Page 404 pour les route inconnue.

3. Stratégie de tests
    * **Unitaires (PHPUnit):** logique métier isolée
    * **Intégration (PHPUnit):** intéractions contrôleurs modèles - MySQL (DB de test dédiée).
    * **End-to-End (Playwright):** parcours utilisateur dans un navigateur.

4. Plan de test
    * Unitaires:

    | ID | Fonctionnalité | Étapes | Résultat attendu |
    |----|----------------|--------|------------------|
    | U1 | Vérification de emailExists() | Appeler avec un email existant | Retourne True |
    | U2 | Vérification de emailExist() avec un email absent | Appeler un email non présent | Retourne false |
    | U3 | Ajout favori en DB | ajoute un favori avec addFavori(1, 123, "film") | Retourne true |
    | U4 | Lecture commentaire par ID | Insérer un commentaire puis utilisé getCommentById(id) | Retourne le contenu |
    | U5 | Vérification route Index | Lire le config du Routeur | Retourne HomeController |

