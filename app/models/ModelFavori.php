<?php

namespace App\Models;

use config\Connexion;

class ModelFavori
{

    private $connexion;

    public function __construct()
    {
        $conn = new Connexion();
        $this->connexion = $conn->connexionBDD();
        $this->connexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getAllFavoriByID($id)
    {
        if (!is_numeric($id)) {
            throw new \Exception("ID utilisateur invalide");
        }
    
        try {
            $query = "SELECT * FROM favoris WHERE user_id = :id";
            $result = $this->connexion->prepare($query);
            $result->execute(["id" => $id]);
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des favoris : " . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des favoris : " . $e->getMessage());
        }
    }

    public function deleteFavori($idFavori, $userId)
    {
        try {
            $query = "DELETE FROM favoris WHERE id_favori = :idFavori AND user_id = :userId";
            $statement = $this->connexion->prepare($query);
            $statement->execute([
                "idFavori" => $idFavori,
                "userId" => $userId
            ]);
            return $statement->rowCount(); // Retourne le nombre de lignes supprimées
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression du favori : " . $e->getMessage());
            throw new \Exception("Erreur lors de la suppression du favori.");
        }
    }

    public function favoriExists(int $userId, int $mediaId, string $type): bool
{
    $query = "SELECT COUNT(*) FROM favoris WHERE user_id = :userId AND element_id = :mediaId AND element_type = :type";
    $stmt = $this->connexion->prepare($query);
    $stmt->execute([
        'userId' => $userId,
        'mediaId' => $mediaId,
        'type' => $type
    ]);
    return $stmt->fetchColumn() > 0;
}

public function addFavori(int $userId, int $mediaId, string $type, string $title, ?string $posterPath): bool
{
    $query = "INSERT INTO favoris (user_id, element_id, element_type, title, poster_patch) VALUES (:userId, :mediaId, :type, :title, :posterPath)";
    $stmt = $this->connexion->prepare($query);
    return $stmt->execute([
        'userId' => $userId,
        'mediaId' => $mediaId,
        'type' => $type,
        'title' => $title,
        'posterPath' => $posterPath
    ]);
}


}