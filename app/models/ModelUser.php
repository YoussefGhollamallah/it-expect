<?php

namespace App\Models;

use config\Connexion;

class ModelUser
{
    private $connexion;

    public function __construct()
    {
        $conn = new Connexion();
        $this->connexion = $conn->connexionBDD();
        $this->connexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    }

    public function register($firstname, $lastname, $email, $password)
    {
        try {
            if($this->emailExists($email)) {
                return 'Email déjà enregistré';
            } else {
                $requete = $this->connexion->prepare("INSERT INTO user (id, firstname, lastname, email, password, created_at) VALUES (null, ?, ?, ?, ?, NOW())");
                $requete->execute([ $firstname, $lastname, $email, $password ]);
                return 'Inscription réussie';
            }
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de l'inscription : " . $e->getMessage());
        }
    }

    public function login($email, $password)
    {
        try {
            $query = "SELECT * FROM user WHERE email = :email";
            $result = $this->connexion->prepare($query);
            $result->execute(['email' => $email]);
            $user = $result->fetch(\PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['id'];
                return $user;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la connexion : " . $e->getMessage());
        }
    }

    public function emailExists($email)
    {
        try {
            $query = "SELECT * FROM user WHERE email = :email";
            $result = $this->connexion->prepare($query);
            $result->execute(['email' => $email]);
            if ($result->fetch()) {
                return true;
            } else {
                return false;
            }
       
        } catch (\PDOException $e) {
            echo "Erreur lors de la vérification de l'email : " . $e->getMessage();
            return false;
        }
    }
}