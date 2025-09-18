<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class RouteurConfigTest extends TestCase
{
    public function testRoutesContainHomeIndex(): void
    {
        $ref = new ReflectionClass(\App\Classes\Routeur::class);
        $prop = $ref->getProperty('routes');
        $prop->setAccessible(true);
        $router = $ref->newInstanceWithoutConstructor();
        $routes = $prop->getValue($router);

        $this->assertArrayHasKey('index', $routes);
        $this->assertSame('HomeController', $routes['index']['controller']);
        $this->assertSame('home', $routes['index']['method']);
    }
}
