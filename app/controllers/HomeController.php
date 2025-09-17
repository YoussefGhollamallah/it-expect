<?php

namespace App\Controllers;

use App\Classes\View;

class HomeController
{
    public function home()
    {
        $title = "Cinetech";
        $this->renderView("index", ["title" => $title]);
    }

    public function erreur404()
    {
        $title = "Erreur 404";
        $this->renderView("404Erreur", ["title" => $title]);
    }

    public function register()
    {
        $title = "Inscription";
        $this->renderView("register", ["title" => $title]);
    }

    public function favori()
    {
        $title = "Favoris";
        $this->renderView("favori", ["title" => $title]);
    }

    public function login()
    {
        $title = "Connexion";
        $this->renderView("login", ["title" => $title]);
    }

    public function film()
    {
        $title = "Films";
        $this->renderView("film", ["title" => $title]);
    }

    public function serie()
    {
        $title = "Série";
        $this->renderView("serie", ["title" => $title]);
    }

    public function profile()
    {
        $title = "Profil";
        $this->renderView("profile", ["title" => $title]);
    }

    public function detail($id, $type)
    {
        // Vérification de la validité des paramètres
        if (!is_numeric($id) || !in_array($type, ['film', 'serie'])) {
            $this->erreur404();
            return;
        }
    
        // Construire l'URL API en fonction du type
        $mediaUrl = ($type === 'film') 
            ? "https://api.themoviedb.org/3/movie/$id?api_key=ea22993e5b3ec7acfb93c59ddf265f8c&language=fr-FR"
            : "https://api.themoviedb.org/3/tv/$id?api_key=ea22993e5b3ec7acfb93c59ddf265f8c&language=fr-FR";
    
        // Utilisation de cURL pour récupérer les données de l'API
        $ch = curl_init($mediaUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);  // Timeout de 10 secondes
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            // En cas d'erreur cURL
            echo 'Erreur cURL: ' . curl_error($ch);
            $this->erreur404();
            return;
        }
    
        curl_close($ch);
    
        // Décoder la réponse JSON de l'API
        $media = json_decode($response, true);
    
        if ($media && isset($media['id'])) {
            // URL pour les crédits (acteurs et réalisateurs)
            $creditsUrl = ($type === 'film')
                ? "https://api.themoviedb.org/3/movie/$id/credits?api_key=ea22993e5b3ec7acfb93c59ddf265f8c&language=fr-FR"
                : "https://api.themoviedb.org/3/tv/$id/credits?api_key=ea22993e5b3ec7acfb93c59ddf265f8c&language=fr-FR";
    
            // Utilisation de cURL pour récupérer les crédits
            $ch = curl_init($creditsUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout de 10 secondes
            $creditsResponse = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo 'Erreur cURL: ' . curl_error($ch);
                $this->erreur404();
                return;
            }
    
            curl_close($ch);
    
            // Décoder la réponse JSON des crédits
            $credits = json_decode($creditsResponse, true);
    
            // Ajouter les crédits (acteurs et réalisateurs) au tableau $media
            $media['cast'] = $credits['cast'] ?? [];
            $media['crew'] = $credits['crew'] ?? [];
    
            // Afficher la vue de détail
            $this->renderView("detail", ["media" => $media,"type" => $type]);
        } else {
            // Si les détails ne sont pas trouvés, afficher une erreur 404
            $this->erreur404();
        }
    }
    

    public function renderView($viewName, $vars = [])
    {
        $view = new View($viewName);
        $view->setVars($vars);
        $view->render();
    }
}
