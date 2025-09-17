<?php

namespace App\Controllers;

use App\Models\ModelFavori;

class FavorisController
{

    private $ModelFavori;

    public function __construct()
    {
        $this->ModelFavori = new ModelFavori;
    }

    public function getAllFavorisByID($id)
    {
        $result = $this->ModelFavori->getAllFavoriByID($id);
        return $result;
    }

    public function deleteFavori($idFavori, $userId)
    {
        try {
            $deletedRows = $this->ModelFavori->deleteFavori($idFavori, $userId);
            if ($deletedRows > 0) {
                return ["success" => true, "message" => "Favori supprimé avec succès."];
            } else {
                return ["success" => false, "message" => "Aucun favori trouvé ou suppression non autorisée."];
            }
        } catch (\Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function addFavori($userId, $elementId, $elementType, $title, $posterPath)
    {
        try {
            // Vérifie si le favori existe déjà
            if ($this->ModelFavori->favoriExists($userId, $elementId, $elementType)) {
                return ["success" => false, "message" => "Cet élément est déjà dans vos favoris."];
            }

            // Ajout du favori dans la base de données
            $this->ModelFavori->addFavori($userId, $elementId, $elementType, $title, $posterPath);
            return ["success" => true, "message" => "Favori ajouté avec succès."];
        } catch (\Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

}