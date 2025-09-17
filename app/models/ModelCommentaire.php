<?php

namespace App\Models;

use config\Connexion;

class ModelCommentaire
{
    private $connexion;

    public function __construct()
    {
        $conn = new Connexion();
        $this->connexion = $conn->connexionBDD();
        $this->connexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function addComment($content, $id_user, $id_media)
    {
        try {
            $query = "INSERT INTO commentaire (content, id_media, id_user, created_at) VALUES (?, ?, ?, NOW())";
            $result = $this->connexion->prepare($query);
            $result->execute([$content, $id_media, $id_user]);
            return 'Commentaire ajouté avec succès.';
        } catch (\PDOException $e) {
            error_log("Erreur lors de l'ajout du commentaire : " . $e->getMessage());
            throw new \Exception("Erreur lors de l'ajout du commentaire.");
        }
    }

    public function getComments($id_media)
    {
        try {
            $query = "SELECT c.id_commentaire, c.content, u.firstname, u.lastname, c.created_at, c.id_user
                      FROM commentaire c
                      JOIN user u ON c.id_user = u.id
                      WHERE c.id_media = :id_media
                      ORDER BY c.created_at DESC";
            $result = $this->connexion->prepare($query);
            $result->execute(['id_media' => $id_media]);
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des commentaires : " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des commentaires.");
        }
    }

    public function deleteCommentByUser($id_commentaire, $id_user)
    {
        try {
            $query = "DELETE FROM commentaire WHERE id_commentaire = :id_commentaire AND id_user = :id_user";
            $result = $this->connexion->prepare($query);
            $result->execute(['id_commentaire' => $id_commentaire, 'id_user' => $id_user]);
            return 'Commentaire supprimé avec succès.';
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression du commentaire : " . $e->getMessage());
            throw new \Exception("Erreur lors de la suppression du commentaire.");
        }
    }

    public function getCommentById($id_commentaire)
    {
        try {
            $query = "SELECT * FROM commentaire WHERE id_commentaire = :id_commentaire";
            $result = $this->connexion->prepare($query);
            $result->execute(['id_commentaire' => $id_commentaire]);
            return $result->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération du commentaire : " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération du commentaire.");
        }
    }
}
