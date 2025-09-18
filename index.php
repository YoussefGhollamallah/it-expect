<?php

require_once 'config/autoload.php';
require_once "vendor/autoload.php";

use App\Classes\Routeur;

Autoload::start();

// Supporte ?page=... (utilisé par les tests) et ?r=..., sinon 'index'
$request = $_GET['page'] ?? ($_GET['r'] ?? 'index');

$routeur = new Routeur($request);
$routeur->renderController();
