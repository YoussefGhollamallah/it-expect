<?php

namespace App\Controllers;

use App\Models\ModelCommentaire;

class commentaireController
{
    private $ModelCommentaire;

    public function __construct()
    {
        $this->ModelCommentaire = new ModelCommentaire();
    }

    public function addComment($content, $id_user, $id_media): bool
    {
        return $this->ModelCommentaire->addComment($content, $id_user, $id_media);
    }

    public function getComments($id_media)
    {
        // Appel à la méthode du modèle sans le paramètre type
        $result = $this->ModelCommentaire->getComments($id_media);
        return $result;
    }

    public function deleteCommentByUser($id_commentaire, $id_user): bool
    {
        return $this->ModelCommentaire->deleteCommentByUser($id_commentaire, $id_user);
    }

    public function getCommentById($id_commentaire)
    {
        // Appel à la méthode du modèle
        $result = $this->ModelCommentaire->getCommentById($id_commentaire);
        return $result;
    }
}
