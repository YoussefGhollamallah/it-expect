<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RoutingRenderTest extends TestCase
{
    protected function setUp(): void
    {
        // Ajuste le chemin vers la racine réelle du projet
        chdir(dirname(__DIR__, 2)); // remonte depuis tests/Integration jusqu'à la racine
        // active les erreurs au cas où
        ini_set('display_errors', '1');
        error_reporting(E_ALL);
    }

    public function testIndexRendersSomeHtml(): void
    {
        $_GET['page'] = 'index';
        ob_start();
        include __DIR__ . '/../../index.php';
        $html = ob_get_clean();

        // Debug si vide
        if ($html === '') {
            $this->fail("Sortie vide: vérifie les 'require' dans index.php (utilise __DIR__ dans les chemins).");
        }

    $this->assertNotEmpty($html);
    $this->assertNotFalse(stripos($html, 'Cinetech'));
    }

    public function testUnknownRouteFallsBackTo404(): void
    {
        $_GET['page'] = 'totally-unknown';
        ob_start();
        include __DIR__ . '/../../index.php';
        $html = ob_get_clean();

        $this->assertNotEmpty($html);
        // La route inconnue doit rendre la page 404 (layout 404 affiche "Erreur 404")
        $this->assertNotFalse(
            stripos($html, 'Erreur 404')
        );
    }
}
