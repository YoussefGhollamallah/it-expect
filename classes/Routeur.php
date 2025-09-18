<?php

namespace App\Classes;

class Routeur
{
    private $request;
    
    private $routes = [
        "index" => ["controller" => "HomeController", "method" => "home"],
        "404Erreur" => ["controller" => "HomeController", "method" => "erreur404"],
        "register" => ["controller" => "HomeController", "method" => "register"],
        "login" => ["controller" => "HomeController", "method" => "login"],
        "film" => ["controller" => "HomeController", "method" => "film"],
        "serie" => ["controller" => "HomeController", "method" => "serie"],
        "detail" => ["controller" => "HomeController", "method" => "detail"],
        "profile" => ["controller" => "HomeController", "method" => "profile"],
        "favori" => ["controller" => "HomeController", "method" => "favori"],
    ];

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function renderController()
    {
        $request = $this->request;
        
        if (array_key_exists($request, $this->routes)) {
            $controller = "App\Controllers\\" . $this->routes[$request]["controller"];
            $method = $this->routes[$request]["method"];
        
            if (class_exists($controller)) {
                $controllerInstance = new $controller();

                // Gestion de la route 'detail' avec paramètres extraits de l'URL
                if ($request === 'detail') {
                    // Extraire les paramètres de l'URL
                    $urlParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
                 
                    // Vérifiez qu'il y a bien suffisamment de parties dans l'URL et que l'ID est valide
                    if (count($urlParts) >= 4 && is_numeric($urlParts[2]) && in_array($urlParts[3], ['film', 'serie'])) {
                        $id = $urlParts[2];   // L'ID se trouve à l'index 2
                        $type = $urlParts[3]; // Le type (film ou série) se trouve à l'index 3
                
                        // Appel de la méthode du contrôleur
                        if (method_exists($controllerInstance, $method)) {
                            $controllerInstance->$method($id, $type);
                            return;
                        }
                    }
                } else {
                    if (method_exists($controllerInstance, $method)) {
                        $controllerInstance->$method();
                        return;
                    }
                }
            }
        }
        $this->redirect404();
    }
    
    // Méthode pour rediriger vers la page 404
    public function redirect404()
    {
        $controllerName = $this->routes["404Erreur"]["controller"];
        $method = $this->routes["404Erreur"]["method"];

        $fqcn = "App\\Controllers\\" . $controllerName;
        // En production, définir le code de statut et ne pas perturber la sortie de tests
        $appEnv = getenv('APP_ENV') ?: '';
        if ($appEnv !== 'test') {
            if (!headers_sent()) {
                header('HTTP/1.1 404 Not Found', true, 404);
            }
        }
        // Toujours fournir une trace explicite dans la sortie
        echo "Erreur 404\n";
        if (class_exists($fqcn)) {
            $controller = new $fqcn();
            if (method_exists($controller, $method)) {
                $controller->$method();
                return;
            }
        }
    }
}
